<?php
/**
 * 2016 Modified by Piotr Janczyk
 *
 * Website: http://sourceforge.net/projects/simplehtmldom/
 * Acknowledge: Jose Solorzano (https://sourceforge.net/projects/php-html/)
 * Contributions by:
 *     Yousuke Kumakura (Attribute filters)
 *     Vadim Voituk (Negative indexes supports of "find" method)
 *     Antcs (Constructor with automatically load contents either text or file/url)
 *
 * all affected sections have comments starting with "PaperG"
 *
 * Paperg - Added case insensitive testing of the value of the selector.
 * Paperg - Added tag_start for the starting index of tags - NOTE: This works but not accurately.
 *  This tag_start gets counted AFTER \r\n have been crushed out, and after the remove_noice calls so it will not reflect the REAL position of the tag in the source,
 *  it will almost always be smaller by some amount.
 *  We use this to determine how far into the file the tag in question is.  This "percentage will never be accurate as the $dom->size is the "real" number of bytes the dom was created from.
 *  but for most purposes, it's a really good estimation.
 * Paperg - Added the forceTagsClosed to the dom constructor.  Forcing tags closed is great for malformed html, but it CAN lead to parsing errors.
 * Allow the user to tell us how much they trust the html.
 * Paperg add the text and plaintext to the selectors for the find syntax.  plaintext implies text in the innertext of a node.  text implies that the tag is a text node.
 * This allows for us to find tags based on the text they contain.
 * Create find_ancestor_tag to see if a tag is - at any level - inside of another specific tag.
 * Paperg: added parseCharset so that we know about the character set of the source document.
 *  NOTE:  If the user's system has a routine called get_last_retrieve_url_contents_content_type availalbe, we will assume it's returning the content-type header from the
 *  last transfer or curl_exec, and we will parse that and use it in preference to any other method of charset detection.
 *
 * Found infinite loop in the case of broken html in restore_noise.  Rewrote to protect from that.
 * PaperG (John Schlick) Added get_display_size for "IMG" tags.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @author S.C. Chen <me578022@gmail.com>
 * @author John Schlick
 * @author Rus Carroll
 */

namespace pjanczyk\lo1olkusz\SimpleHtmlDom;

class DomType
{
    const ELEMENT = 1;
    const COMMENT = 2;
    const TEXT = 3;
    const ENDTAG = 4;
    const ROOT = 5;
    const UNKNOWN = 6;
}

class DomInfo
{
    const BEGIN = 0;
    const END = 1;
    const QUOTE = 2;
    const SPACE = 3;
    const TEXT = 4;
    const INNER = 5;
    const OUTER = 6;
    const ENDSPACE = 7;
}

class QuoteType
{
    const SINGLE = 0;
    const DOUBLE = 1;
    const NONE = 3;
}

class SimpleHtmlDom
{
    /** @var SimpleHtmlDomNode|null */
    public $root = null;
    /** @var SimpleHtmlDomNode[] */
    public $nodes = array();
    public $lowercase = false;
    // Used to keep track of how large the text was when we started.
    public $original_size;
    public $size;
    protected $pos;
    protected $doc;
    protected $char;
    protected $cursor;
    /** @var SimpleHtmlDomNode|null */
    protected $parent;
    protected $noise = array();
    protected $token_blank = " \t\r\n";
    protected $token_equal = ' =/>';
    protected $token_slash = " />\r\n\t";
    protected $token_attr = ' >';
    // Note that this is referenced by a child node, and so it needs to be public for that node to see this information.
    public $_charset = '';
    public $_target_charset = '';
    const BR_TEXT = "\n";
    const DEFAULT_CHARSET = 'UTF-8';

    // use isset instead of in_array, performance boost about 30%...
    protected $self_closing_tags = array('img' => 1, 'br' => 1, 'input' => 1, 'meta' => 1, 'link' => 1, 'hr' => 1, 'base' => 1, 'embed' => 1, 'spacer' => 1);
    protected $block_tags = array('root' => 1, 'body' => 1, 'form' => 1, 'div' => 1, 'span' => 1, 'table' => 1);
    // Known sourceforge issue #2977341
    // B tags that are not closed cause us to return everything to the end of the document.
    protected $optional_closing_tags = array(
        'tr' => array('tr' => 1, 'td' => 1, 'th' => 1),
        'th' => array('th' => 1),
        'td' => array('td' => 1),
        'li' => array('li' => 1),
        'dt' => array('dt' => 1, 'dd' => 1),
        'dd' => array('dd' => 1, 'dt' => 1),
        'dl' => array('dd' => 1, 'dt' => 1),
        'p' => array('p' => 1),
        'nobr' => array('nobr' => 1),
        'b' => array('b' => 1),
        'option' => array('option' => 1),
    );

    /**
     * @param $url
     * @param bool $lowercase
     * @param bool $forceTagsClosed
     * @param string $target_charset
     * @param bool $stripRN
     * @return null|SimpleHtmlDom
     */
    public static function fromUrl($url, $lowercase = true, $forceTagsClosed = true, $target_charset = self::DEFAULT_CHARSET, $stripRN = true)
    {
        $str = file_get_contents($url);
        if ($str === false) {
            return null;
        }
        else {
            return self::fromString($str, $lowercase, $forceTagsClosed, $target_charset, $stripRN);
        }
    }

    /**
     * @param $str
     * @param bool $lowercase
     * @param bool $forceTagsClosed
     * @param string $target_charset
     * @param bool $stripRN
     * @return SimpleHtmlDom
     */
    public static function fromString($str, $lowercase = true, $forceTagsClosed = true, $target_charset = self::DEFAULT_CHARSET, $stripRN = true)
    {
        $dom = new self(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN);
        $dom->load($str, $lowercase, $stripRN);
        return $dom;
    }

    private function __construct($str = null, $lowercase = true, $forceTagsClosed = true, $target_charset = self::DEFAULT_CHARSET, $stripRN = true)
    {
        if ($str) {
            $this->load($str, $lowercase, $stripRN);
        }
        // Forcing tags to be closed implies that we don't trust the html, but it can lead to parsing errors if we SHOULD trust the html.
        if (!$forceTagsClosed) {
            $this->optional_closing_array = array();
        }
        $this->_target_charset = $target_charset;
    }

    function __destruct()
    {
        $this->clear();
    }

    // load html from string
    function load($str, $lowercase = true, $stripRN = true)
    {
        // prepare
        $this->prepare($str, $lowercase, $stripRN);
        // strip out comments
        $this->removeNoise("'<!--(.*?)-->'is");
        // strip out cdata
        $this->removeNoise("'<!\[CDATA\[(.*?)\]\]>'is", true);
        // Per sourceforge http://sourceforge.net/tracker/?func=detail&aid=2949097&group_id=218559&atid=1044037
        // Script tags removal now preceeds style tag removal.
        // strip out <script> tags
        $this->removeNoise("'<\s*script[^>]*[^/]>(.*?)<\s*/\s*script\s*>'is");
        $this->removeNoise("'<\s*script\s*>(.*?)<\s*/\s*script\s*>'is");
        // strip out <style> tags
        $this->removeNoise("'<\s*style[^>]*[^/]>(.*?)<\s*/\s*style\s*>'is");
        $this->removeNoise("'<\s*style\s*>(.*?)<\s*/\s*style\s*>'is");
        // strip out preformatted tags
        $this->removeNoise("'<\s*(?:code)[^>]*>(.*?)<\s*/\s*(?:code)\s*>'is");
        // strip out server side scripts
        $this->removeNoise("'(<\?)(.*?)(\?>)'s", true);
        // strip smarty scripts
        $this->removeNoise("'(\{\w)(.*?)(\})'s", true);

        // parsing
        while ($this->parse()) ;
        // end
        $this->root->_[DomInfo::END] = $this->cursor;
        $this->parseCharset();

        // make load function chainable
        return $this;

    }

    /**
     * @return null|SimpleHtmlDomNode
     */
    public function root()
    {
        return $this->root;
    }

    // clean up memory due to php5 circular references memory leak...
    function clear()
    {
        foreach ($this->nodes as $n) {
            $n->clear();
            $n = null;
        }
        // This add next line is documented in the sourceforge repository. 2977248 as a fix for ongoing memory leaks that occur even with the use of clear.
        if (isset($this->children)) foreach ($this->children as $n) {
            $n->clear();
            $n = null;
        }
        if (isset($this->parent)) {
            $this->parent->clear();
            unset($this->parent);
        }
        if (isset($this->root)) {
            $this->root->clear();
            unset($this->root);
        }
        unset($this->doc);
        unset($this->noise);
    }

    // prepare HTML data and init everything
    protected function prepare($str, $lowercase = true, $stripRN = true)
    {
        $this->clear();

        // set the length of content before we do anything to it.
        $this->size = strlen($str);
        // Save the original size of the html that we got in.  It might be useful to someone.
        $this->original_size = $this->size;

        //before we save the string as the doc...  strip out the \r \n's if we are told to.
        if ($stripRN) {
            $str = str_replace("\r", " ", $str);
            $str = str_replace("\n", " ", $str);

            // set the length of content since we have changed it.
            $this->size = strlen($str);
        }

        $this->doc = $str;
        $this->pos = 0;
        $this->cursor = 1;
        $this->noise = array();
        $this->nodes = array();
        $this->lowercase = $lowercase;
        $this->root = new SimpleHtmlDomNode($this);
        $this->root->tag = 'root';
        $this->root->_[DomInfo::BEGIN] = -1;
        $this->root->type = DomType::ROOT;
        $this->parent = $this->root;
        if ($this->size > 0) $this->char = $this->doc[0];
    }

    // parse html content
    protected function parse()
    {
        if (($s = $this->copy_until_char('<')) === '') {
            return $this->read_tag();
        }

        // text
        $node = new SimpleHtmlDomNode($this);
        ++$this->cursor;
        $node->_[DomInfo::TEXT] = $s;
        $this->link_nodes($node, false);
        return true;
    }

    // PAPERG - dkchou - added this to try to identify the character set of the page we have just parsed so we know better how to spit it out later.
    // NOTE:  IF you provide a routine called get_last_retrieve_url_contents_content_type which returns the CURLINFO_CONTENT_TYPE from the last curl_exec
    // (or the content_type header from the last transfer), we will parse THAT, and if a charset is specified, we will use it over any other mechanism.
    protected function parseCharset()
    {
        $charset = null;

        if (function_exists('get_last_retrieve_url_contents_content_type')) {
            $contentTypeHeader = get_last_retrieve_url_contents_content_type();
            $success = preg_match('/charset=(.+)/', $contentTypeHeader, $matches);
            if ($success) {
                $charset = $matches[1];
            }
        }

        if (empty($charset)) {
            $el = $this->root->find('meta[http-equiv=Content-Type]', 0);
            if (!empty($el)) {
                $fullValue = $el->getAttribute("content");

                if (!empty($fullValue)) {
                    $success = preg_match('/charset=(.+)/', $fullValue, $matches);
                    if ($success) {
                        $charset = $matches[1];
                    }
                    else {
                        // If there is a meta tag, and they don't specify the character set, research says that it's typically ISO-8859-1
                        $charset = 'ISO-8859-1';
                    }
                }
            }
        }

        // If we couldn't find a charset above, then lets try to detect one based on the text we got...
        if (empty($charset)) {
            // Have php try to detect the encoding from the text given to us.
            $charset = mb_detect_encoding($this->root->text() . "ascii", $encoding_list = array("UTF-8", "CP1252"));

            // and if this doesn't work...  then we need to just wrongheadedly assume it's UTF-8 so that we can move on - cause this will usually give us most of what we need...
            if ($charset === false) {
                $charset = 'UTF-8';
            }
        }

        // Since CP1252 is a superset, if we get one of it's subsets, we want it instead.
        if ((strtolower($charset) == strtolower('ISO-8859-1')) || (strtolower($charset) == strtolower('Latin1')) || (strtolower($charset) == strtolower('Latin-1'))) {
            $charset = 'CP1252';
        }

        return $this->_charset = $charset;
    }

    // read tag info
    protected function read_tag()
    {
        if ($this->char !== '<') {
            $this->root->_[DomInfo::END] = $this->cursor;
            return false;
        }
        $begin_tag_pos = $this->pos;
        $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next

        // end tag
        if ($this->char === '/') {
            $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
            // This represents the change in the simple_html_dom trunk from revision 180 to 181.
            // $this->skip($this->token_blank_t);
            $this->skip($this->token_blank);
            $tag = $this->copy_until_char('>');

            // skip attributes in end tag
            if (($pos = strpos($tag, ' ')) !== false)
                $tag = substr($tag, 0, $pos);

            $parent_lower = strtolower($this->parent->tag);
            $tag_lower = strtolower($tag);

            if ($parent_lower !== $tag_lower) {
                if (isset($this->optional_closing_tags[$parent_lower]) && isset($this->block_tags[$tag_lower])) {
                    $this->parent->_[DomInfo::END] = 0;
                    $org_parent = $this->parent;

                    while (($this->parent->parent) && strtolower($this->parent->tag) !== $tag_lower)
                        $this->parent = $this->parent->parent;

                    if (strtolower($this->parent->tag) !== $tag_lower) {
                        $this->parent = $org_parent; // restore origonal parent
                        if ($this->parent->parent) $this->parent = $this->parent->parent;
                        $this->parent->_[DomInfo::END] = $this->cursor;
                        return $this->as_text_node($tag);
                    }
                }
                else if (($this->parent->parent) && isset($this->block_tags[$tag_lower])) {
                    $this->parent->_[DomInfo::END] = 0;
                    $org_parent = $this->parent;

                    while (($this->parent->parent) && strtolower($this->parent->tag) !== $tag_lower)
                        $this->parent = $this->parent->parent;

                    if (strtolower($this->parent->tag) !== $tag_lower) {
                        $this->parent = $org_parent; // restore origonal parent
                        $this->parent->_[DomInfo::END] = $this->cursor;
                        return $this->as_text_node($tag);
                    }
                }
                else if (($this->parent->parent) && strtolower($this->parent->parent->tag) === $tag_lower) {
                    $this->parent->_[DomInfo::END] = 0;
                    $this->parent = $this->parent->parent;
                }
                else
                    return $this->as_text_node($tag);
            }

            $this->parent->_[DomInfo::END] = $this->cursor;
            if ($this->parent->parent) $this->parent = $this->parent->parent;

            $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
            return true;
        }

        $node = new SimpleHtmlDomNode($this);
        $node->_[DomInfo::BEGIN] = $this->cursor;
        ++$this->cursor;
        $tag = $this->copy_until($this->token_slash);
        $node->tag_start = $begin_tag_pos;

        // doctype, cdata & comments...
        if (isset($tag[0]) && $tag[0] === '!') {
            $node->_[DomInfo::TEXT] = '<' . $tag . $this->copy_until_char('>');

            if (isset($tag[2]) && $tag[1] === '-' && $tag[2] === '-') {
                $node->type = DomType::COMMENT;
                $node->tag = 'comment';
            }
            else {
                $node->type = DomType::UNKNOWN;
                $node->tag = 'unknown';
            }
            if ($this->char === '>') $node->_[DomInfo::TEXT] .= '>';
            $this->link_nodes($node, true);
            $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
            return true;
        }

        // text
        if ($pos = strpos($tag, '<') !== false) {
            $tag = '<' . substr($tag, 0, -1);
            $node->_[DomInfo::TEXT] = $tag;
            $this->link_nodes($node, false);
            $this->char = $this->doc[--$this->pos]; // prev
            return true;
        }

        if (!preg_match("/^[\w-:]+$/", $tag)) {
            $node->_[DomInfo::TEXT] = '<' . $tag . $this->copy_until('<>');
            if ($this->char === '<') {
                $this->link_nodes($node, false);
                return true;
            }

            if ($this->char === '>') $node->_[DomInfo::TEXT] .= '>';
            $this->link_nodes($node, false);
            $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
            return true;
        }

        // begin tag
        $node->type = DomType::ELEMENT;
        $tag_lower = strtolower($tag);
        $node->tag = ($this->lowercase) ? $tag_lower : $tag;

        // handle optional closing tags
        if (isset($this->optional_closing_tags[$tag_lower])) {
            while (isset($this->optional_closing_tags[$tag_lower][strtolower($this->parent->tag)])) {
                $this->parent->_[DomInfo::END] = 0;
                $this->parent = $this->parent->parent;
            }
            $node->parent = $this->parent;
        }

        $guard = 0; // prevent infinity loop
        $space = array($this->copy_skip($this->token_blank), '', '');

        // attributes
        do {
            if ($this->char !== null && $space[0] === '') {
                break;
            }
            $name = $this->copy_until($this->token_equal);
            if ($guard === $this->pos) {
                $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
                continue;
            }
            $guard = $this->pos;

            // handle endless '<'
            if ($this->pos >= $this->size - 1 && $this->char !== '>') {
                $node->type = DomType::TEXT;
                $node->_[DomInfo::END] = 0;
                $node->_[DomInfo::TEXT] = '<' . $tag . $space[0] . $name;
                $node->tag = 'text';
                $this->link_nodes($node, false);
                return true;
            }

            // handle mismatch '<'
            if ($this->doc[$this->pos - 1] == '<') {
                $node->type = DomType::TEXT;
                $node->tag = 'text';
                $node->attr = array();
                $node->_[DomInfo::END] = 0;
                $node->_[DomInfo::TEXT] = substr($this->doc, $begin_tag_pos, $this->pos - $begin_tag_pos - 1);
                $this->pos -= 2;
                $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
                $this->link_nodes($node, false);
                return true;
            }

            if ($name !== '/' && $name !== '') {
                $space[1] = $this->copy_skip($this->token_blank);
                $name = $this->restore_noise($name);
                if ($this->lowercase) $name = strtolower($name);
                if ($this->char === '=') {
                    $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
                    $this->parse_attr($node, $name, $space);
                }
                else {
                    //no value attr: nowrap, checked selected...
                    $node->_[DomInfo::QUOTE][] = QuoteType::NONE;
                    $node->attr[$name] = true;
                    if ($this->char != '>') $this->char = $this->doc[--$this->pos]; // prev
                }
                $node->_[DomInfo::SPACE][] = $space;
                $space = array($this->copy_skip($this->token_blank), '', '');
            }
            else
                break;
        } while ($this->char !== '>' && $this->char !== '/');

        $this->link_nodes($node, true);
        $node->_[DomInfo::ENDSPACE] = $space[0];

        // check self closing
        if ($this->copy_until_char_escape('>') === '/') {
            $node->_[DomInfo::ENDSPACE] .= '/';
            $node->_[DomInfo::END] = 0;
        }
        else {
            // reset parent
            if (!isset($this->self_closing_tags[strtolower($node->tag)])) $this->parent = $node;
        }
        $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next

        // If it's a BR tag, we need to set it's text to the default text.
        // This way when we see it in plaintext, we can generate formatting that the user wants.
        // since a br tag never has sub nodes, this works well.
        if ($node->tag == "br") {
            $node->_[DomInfo::INNER] = self::BR_TEXT;
        }

        return true;
    }

    // parse attributes
    protected function parse_attr($node, $name, &$space)
    {
        // Per sourceforge: http://sourceforge.net/tracker/?func=detail&aid=3061408&group_id=218559&atid=1044037
        // If the attribute is already defined inside a tag, only pay atetntion to the first one as opposed to the last one.
        if (isset($node->attr[$name])) {
            return;
        }

        $space[2] = $this->copy_skip($this->token_blank);
        switch ($this->char) {
            case '"':
                $node->_[DomInfo::QUOTE][] = QuoteType::DOUBLE;
                $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
                $node->attr[$name] = $this->restore_noise($this->copy_until_char_escape('"'));
                $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
                break;
            case '\'':
                $node->_[DomInfo::QUOTE][] = QuoteType::SINGLE;
                $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
                $node->attr[$name] = $this->restore_noise($this->copy_until_char_escape('\''));
                $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
                break;
            default:
                $node->_[DomInfo::QUOTE][] = QuoteType::NONE;
                $node->attr[$name] = $this->restore_noise($this->copy_until($this->token_attr));
        }
        // PaperG: Attributes should not have \r or \n in them, that counts as html whitespace.
        $node->attr[$name] = str_replace("\r", "", $node->attr[$name]);
        $node->attr[$name] = str_replace("\n", "", $node->attr[$name]);
        // PaperG: If this is a "class" selector, lets get rid of the preceeding and trailing space since some people leave it in the multi class case.
        if ($name == "class") {
            $node->attr[$name] = trim($node->attr[$name]);
        }
    }

    // link node's parent
    protected function link_nodes(&$node, $is_child)
    {
        $node->parent = $this->parent;
        $this->parent->nodes[] = $node;
        if ($is_child) {
            $this->parent->children[] = $node;
        }
    }

    // as a text node
    protected function as_text_node($tag)
    {
        $node = new SimpleHtmlDomNode($this);
        ++$this->cursor;
        $node->_[DomInfo::TEXT] = '</' . $tag . '>';
        $this->link_nodes($node, false);
        $this->char = (++$this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
        return true;
    }

    protected function skip($chars)
    {
        $this->pos += strspn($this->doc, $chars, $this->pos);
        $this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
    }

    protected function copy_skip($chars)
    {
        $pos = $this->pos;
        $len = strspn($this->doc, $chars, $pos);
        $this->pos += $len;
        $this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
        if ($len === 0) return '';
        return substr($this->doc, $pos, $len);
    }

    protected function copy_until($chars)
    {
        $pos = $this->pos;
        $len = strcspn($this->doc, $chars, $pos);
        $this->pos += $len;
        $this->char = ($this->pos < $this->size) ? $this->doc[$this->pos] : null; // next
        return substr($this->doc, $pos, $len);
    }

    protected function copy_until_char($char)
    {
        if ($this->char === null) return '';

        if (($pos = strpos($this->doc, $char, $this->pos)) === false) {
            $ret = substr($this->doc, $this->pos, $this->size - $this->pos);
            $this->char = null;
            $this->pos = $this->size;
            return $ret;
        }

        if ($pos === $this->pos) return '';
        $pos_old = $this->pos;
        $this->char = $this->doc[$pos];
        $this->pos = $pos;
        return substr($this->doc, $pos_old, $pos - $pos_old);
    }

    protected function copy_until_char_escape($char)
    {
        if ($this->char === null) return '';

        $start = $this->pos;
        while (1) {
            if (($pos = strpos($this->doc, $char, $start)) === false) {
                $ret = substr($this->doc, $this->pos, $this->size - $this->pos);
                $this->char = null;
                $this->pos = $this->size;
                return $ret;
            }

            if ($pos === $this->pos) return '';

            if ($this->doc[$pos - 1] === '\\') {
                $start = $pos + 1;
                continue;
            }

            $pos_old = $this->pos;
            $this->char = $this->doc[$pos];
            $this->pos = $pos;
            return substr($this->doc, $pos_old, $pos - $pos_old);
        }
    }

    // remove noise from html content
    // save the noise in the $this->noise array.
    protected function removeNoise($pattern, $remove_tag = false)
    {
        $count = preg_match_all($pattern, $this->doc, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE);

        for ($i = $count - 1; $i > -1; --$i) {
            $key = '___noise___' . sprintf('% 5d', count($this->noise) + 1000);
            $idx = ($remove_tag) ? 0 : 1;
            $this->noise[$key] = $matches[$i][$idx][0];
            $this->doc = substr_replace($this->doc, $key, $matches[$i][$idx][1], strlen($matches[$i][$idx][0]));
        }

        // reset the length of content
        $this->size = strlen($this->doc);
        if ($this->size > 0) {
            $this->char = $this->doc[0];
        }
    }

    // restore noise to html content
    function restore_noise($text)
    {
        while (($pos = strpos($text, '___noise___')) !== false) {
            // Sometimes there is a broken piece of markup, and we don't GET the pos+11 etc... token which indicates a problem outside of us...
            if (strlen($text) > $pos + 15) {
                $key = '___noise___' . $text[$pos + 11] . $text[$pos + 12] . $text[$pos + 13] . $text[$pos + 14] . $text[$pos + 15];

                if (isset($this->noise[$key])) {
                    $text = substr($text, 0, $pos) . $this->noise[$key] . substr($text, $pos + 16);
                }
                else {
                    // do this to prevent an infinite loop.
                    $text = substr($text, 0, $pos) . 'UNDEFINED NOISE FOR KEY: ' . $key . substr($text, $pos + 16);
                }
            }
            else {
                // There is no valid key being given back to us... We must get rid of the ___noise___ or we will have a problem.
                $text = substr($text, 0, $pos) . 'NO NUMERIC NOISE KEY' . substr($text, $pos + 11);
            }
        }
        return $text;
    }

    // Sometimes we NEED one of the noise elements.
    function search_noise($text)
    {
        foreach ($this->noise as $noiseElement) {
            if (strpos($noiseElement, $text) !== false) {
                return $noiseElement;
            }
        }
    }
}