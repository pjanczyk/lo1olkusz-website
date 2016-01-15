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

class SimpleHtmlDomNode
{
    public $type = DomType::TEXT;
    public $tag = 'text';
    public $attr = array();
    public $children = array();
    /** @var SimpleHtmlDomNode[] */
    public $nodes = array();
    /** @var SimpleHtmlDomNode|null */
    public $parent = null;
    // The "info" array - see DomInfo::... for what each element contains.
    public $_ = array();
    public $tag_start = 0;
    /** @var SimpleHtmlDom */
    private $dom = null;

    public function __construct($dom)
    {
        $this->dom = $dom;
        $dom->nodes[] = $this;
    }

    public function __destruct()
    {
        $this->clear();
    }

    public function __toString()
    {
        return $this->outerText();
    }

    // clean up memory due to php5 circular references memory leak...
    public function clear()
    {
        $this->dom = null;
        $this->nodes = null;
        $this->parent = null;
        $this->children = null;
    }

    /**
     * Returns the parent of the node
     * @return null|SimpleHtmlDomNode
     */
    public function parent()
    {
        return $this->parent;
    }

    public function hasChildren()
    {
        return !empty($this->children);
    }

    public function children()
    {
        return $this->children;
    }

    public function child($idx)
    {
        if (isset($this->children[$idx])) return $this->children[$idx];
        return null;
    }

    public function firstChild()
    {
        return $this->child(0);
    }

    public function lastChild()
    {
        return $this->child(count($this->children));
    }

    public function getAllAttributes()
    {
        return $this->attr;
    }

    public function getAttribute($name)
    {
        if (isset($this->attr[$name])) {
            return $this->convert_text($this->attr[$name]);
        }
        else {
            return false;
        }
    }

    public function hasAttribute($name)
    {
        //no value attr: nowrap, checked selected...
        return (array_key_exists($name, $this->attr)) ? true : isset($this->attr[$name]);
    }

    public function getTag()
    {
        return $this->tag;
    }

    /**
     * Gets dom node's inner html
     * @return string
     */
    public function innerText()
    {
        if (isset($this->_[DomInfo::INNER])) return $this->_[DomInfo::INNER];
        if (isset($this->_[DomInfo::TEXT])) return $this->dom->restore_noise($this->_[DomInfo::TEXT]);

        $ret = '';
        foreach ($this->nodes as $n)
            $ret .= $n->outerText();
        return $ret;
    }

    /**
     * Gets dom node's outer text (with tag)
     * @return string
     */
    public function outerText()
    {
        if ($this->tag === 'root') return $this->innerText();

        if (isset($this->_[DomInfo::OUTER])) return $this->_[DomInfo::OUTER];
        if (isset($this->_[DomInfo::TEXT])) return $this->dom->restore_noise($this->_[DomInfo::TEXT]);

        // render begin tag
        if ($this->dom && $this->dom->nodes[$this->_[DomInfo::BEGIN]]) {
            $ret = $this->dom->nodes[$this->_[DomInfo::BEGIN]]->makeup();
        }
        else {
            $ret = "";
        }

        // render inner text
        if (isset($this->_[DomInfo::INNER])) {
            // If it's a br tag...  don't return the HDOM_INNER_INFO that we may or may not have added.
            if ($this->tag != "br") {
                $ret .= $this->_[DomInfo::INNER];
            }
        }
        else {
            if ($this->nodes) {
                foreach ($this->nodes as $n) {
                    $ret .= $this->convert_text($n->outerText());
                }
            }
        }

        // render end tag
        if (isset($this->_[DomInfo::END]) && $this->_[DomInfo::END] != 0)
            $ret .= '</' . $this->tag . '>';
        return $ret;
    }

    /**
     * Gets dom node's plain text
     * @return string
     */
    function text()
    {
        if (isset($this->_[DomInfo::INNER])) return $this->_[DomInfo::INNER];
        switch ($this->type) {
            case DomType::TEXT:
                return $this->dom->restore_noise($this->_[DomInfo::TEXT]);
            case DomType::COMMENT:
                return '';
            case DomType::UNKNOWN:
                return '';
        }
        if (strcasecmp($this->tag, 'script') === 0) return '';
        if (strcasecmp($this->tag, 'style') === 0) return '';

        $ret = '';
        // In rare cases, (always node type 1 or DomType::ELEMENT - observed for some span tags, and some p tags) $this->nodes is set to NULL.
        // NOTE: This indicates that there is a problem where it's set to NULL without a clear happening.
        // WHY is this happening?
        if (!is_null($this->nodes)) {
            foreach ($this->nodes as $n) {
                $ret .= $this->convert_text($n->text());
            }

            // If this node is a span... add a space at the end of it so multiple spans don't run into each other.  This is plaintext after all.
            if ($this->tag == "span") {
                $ret .= ' ';
            }


        }
        return $ret;
    }

    function xmltext()
    {
        $ret = $this->innerText();
        $ret = str_ireplace('<![CDATA[', '', $ret);
        $ret = str_replace(']]>', '', $ret);
        return $ret;
    }

    // build node's text with tag
    private function makeup()
    {
        // text, comment, unknown
        if (isset($this->_[DomInfo::TEXT])) return $this->dom->restore_noise($this->_[DomInfo::TEXT]);

        $ret = '<' . $this->tag;
        $i = -1;

        foreach ($this->attr as $key => $val) {
            ++$i;

            // skip removed attribute
            if ($val === null || $val === false)
                continue;

            $ret .= $this->_[DomInfo::SPACE][$i][0];
            //no value attr: nowrap, checked selected...
            if ($val === true)
                $ret .= $key;
            else {
                switch ($this->_[DomInfo::QUOTE][$i]) {
                    case QuoteType::DOUBLE:
                        $quote = '"';
                        break;
                    case QuoteType::SINGLE:
                        $quote = '\'';
                        break;
                    default:
                        $quote = '';
                }
                $ret .= $key . $this->_[DomInfo::SPACE][$i][1] . '=' . $this->_[DomInfo::SPACE][$i][2] . $quote . $val . $quote;
            }
        }
        $ret = $this->dom->restore_noise($ret);
        return $ret . $this->_[DomInfo::ENDSPACE] . '>';
    }

    /**
     * Finds elements by css selector
     * @param string $selector
     * @param null $idx
     * @param bool $lowercase
     * @return null|SimpleHtmlDomNode|SimpleHtmlDomNode[]
     */
    public function find($selector, $idx = null, $lowercase = false)
    {
        $selectors = $this->parseSelector($selector);
        if (($count = count($selectors)) === 0) return array();
        $found_keys = array();

        // find each selector
        for ($c = 0; $c < $count; ++$c) {
            // The change on the below line was documented on the sourceforge code tracker id 2788009
            // used to be: if (($levle=count($selectors[0]))===0) return array();
            if (($levle = count($selectors[$c])) === 0) return array();
            if (!isset($this->_[DomInfo::BEGIN])) return array();

            $head = array($this->_[DomInfo::BEGIN] => 1);

            // handle descendant selectors, no recursive!
            for ($l = 0; $l < $levle; ++$l) {
                $ret = array();
                foreach ($head as $k => $v) {
                    $n = ($k === -1) ? $this->dom->root : $this->dom->nodes[$k];
                    //PaperG - Pass this optional parameter on to the seek function.
                    $n->seek($selectors[$c][$l], $ret, $lowercase);
                }
                $head = $ret;
            }

            foreach ($head as $k => $v) {
                if (!isset($found_keys[$k]))
                    $found_keys[$k] = 1;
            }
        }

        // sort keys
        ksort($found_keys);

        /** @var SimpleHtmlDomNode[] $found */
        $found = array();
        foreach ($found_keys as $k => $v)
            $found[] = $this->dom->nodes[$k];

        // return nth-element or array
        if (is_null($idx)) return $found;
        else if ($idx < 0) $idx = count($found) + $idx;
        return (isset($found[$idx])) ? $found[$idx] : null;
    }

    // seek for given conditions
    // PaperG - added parameter to allow for case insensitive testing of the value of a selector.
    protected function seek($selector, &$ret, $lowercase = false)
    {
        list($tag, $key, $val, $exp, $no_key) = $selector;

        // xpath index
        if ($tag && $key && is_numeric($key)) {
            $count = 0;
            foreach ($this->children as $c) {
                if ($tag === '*' || $tag === $c->tag) {
                    if (++$count == $key) {
                        $ret[$c->_[DomInfo::BEGIN]] = 1;
                        return;
                    }
                }
            }
            return;
        }

        $end = (!empty($this->_[DomInfo::END])) ? $this->_[DomInfo::END] : 0;
        if ($end == 0) {
            $parent = $this->parent;
            while (!isset($parent->_[DomInfo::END]) && $parent !== null) {
                $end -= 1;
                $parent = $parent->parent;
            }
            $end += $parent->_[DomInfo::END];
        }

        for ($i = $this->_[DomInfo::BEGIN] + 1; $i < $end; ++$i) {
            $node = $this->dom->nodes[$i];

            $pass = true;

            if ($tag === '*' && !$key) {
                if (in_array($node, $this->children, true))
                    $ret[$i] = 1;
                continue;
            }

            // compare tag
            if ($tag && $tag != $node->tag && $tag !== '*') {
                $pass = false;
            }
            // compare key
            if ($pass && $key) {
                if ($no_key) {
                    if (isset($node->attr[$key])) $pass = false;
                }
                else {
                    if (($key != "plaintext") && !isset($node->attr[$key])) $pass = false;
                }
            }
            // compare value
            if ($pass && $key && $val && $val !== '*') {
                // If they have told us that this is a "plaintext" search then we want the plaintext of the node - right?
                if ($key == "plaintext") {
                    // $node->plaintext actually returns $node->text();
                    $nodeKeyValue = $node->text();
                }
                else {
                    // this is a normal search, we want the value of that attribute of the tag.
                    $nodeKeyValue = $node->attr[$key];
                }

                //PaperG - If lowercase is set, do a case insensitive test of the value of the selector.
                if ($lowercase) {
                    $check = $this->match($exp, strtolower($val), strtolower($nodeKeyValue));
                }
                else {
                    $check = $this->match($exp, $val, $nodeKeyValue);
                }

                // handle multiple class
                if (!$check && strcasecmp($key, 'class') === 0) {
                    foreach (explode(' ', $node->attr[$key]) as $k) {
                        // Without this, there were cases where leading, trailing, or double spaces lead to our comparing blanks - bad form.
                        if (!empty($k)) {
                            if ($lowercase) {
                                $check = $this->match($exp, strtolower($val), strtolower($k));
                            }
                            else {
                                $check = $this->match($exp, $val, $k);
                            }
                            if ($check) break;
                        }
                    }
                }
                if (!$check) $pass = false;
            }
            if ($pass) $ret[$i] = 1;
            unset($node);
        }
    }

    protected function match($exp, $pattern, $value)
    {
        switch ($exp) {
            case '=':
                return ($value === $pattern);
            case '!=':
                return ($value !== $pattern);
            case '^=':
                return preg_match("/^" . preg_quote($pattern, '/') . "/", $value);
            case '$=':
                return preg_match("/" . preg_quote($pattern, '/') . "$/", $value);
            case '*=':
                if ($pattern[0] == '/') {
                    return preg_match($pattern, $value);
                }
                return preg_match("/" . $pattern . "/i", $value);
        }
        return false;
    }

    protected function parseSelector($selector_string)
    {
        // pattern of CSS selectors, modified from mootools
        // Paperg: Add the colon to the attrbute, so that it properly finds <tag attr:ibute="something" > like google does.
        // Note: if you try to look at this attribute, yo MUST use getAttribute since $dom->x:y will fail the php syntax check.
// Notice the \[ starting the attbute?  and the @? following?  This implies that an attribute can begin with an @ sign that is not captured.
// This implies that an html attribute specifier may start with an @ sign that is NOT captured by the expression.
// farther study is required to determine of this should be documented or removed.
//        $pattern = "/([\w-:\*]*)(?:\#([\w-]+)|\.([\w-]+))?(?:\[@?(!?[\w-]+)(?:([!*^$]?=)[\"']?(.*?)[\"']?)?\])?([\/, ]+)/is";
        $pattern = "/([\w-:\*]*)(?:\#([\w-]+)|\.([\w-]+))?(?:\[@?(!?[\w-:]+)(?:([!*^$]?=)[\"']?(.*?)[\"']?)?\])?([\/, ]+)/is";
        preg_match_all($pattern, trim($selector_string) . ' ', $matches, PREG_SET_ORDER);

        $selectors = array();
        $result = array();
        //print_r($matches);

        foreach ($matches as $m) {
            $m[0] = trim($m[0]);
            if ($m[0] === '' || $m[0] === '/' || $m[0] === '//') continue;
            // for browser generated xpath
            if ($m[1] === 'tbody') continue;

            list($tag, $key, $val, $exp, $no_key) = array($m[1], null, null, '=', false);
            if (!empty($m[2])) {
                $key = 'id';
                $val = $m[2];
            }
            if (!empty($m[3])) {
                $key = 'class';
                $val = $m[3];
            }
            if (!empty($m[4])) {
                $key = $m[4];
            }
            if (!empty($m[5])) {
                $exp = $m[5];
            }
            if (!empty($m[6])) {
                $val = $m[6];
            }

            // convert to lowercase
            if ($this->dom->lowercase) {
                $tag = strtolower($tag);
                $key = strtolower($key);
            }
            //elements that do NOT have the specified attribute
            if (isset($key[0]) && $key[0] === '!') {
                $key = substr($key, 1);
                $no_key = true;
            }

            $result[] = array($tag, $key, $val, $exp, $no_key);
            if (trim($m[7]) === ',') {
                $selectors[] = $result;
                $result = array();
            }
        }
        if (count($result) > 0)
            $selectors[] = $result;
        return $selectors;
    }


    // PaperG - Function to convert the text from one character set to another if the two sets are not the same.
    function convert_text($text)
    {
        $converted_text = $text;

        $sourceCharset = "";
        $targetCharset = "";

        if ($this->dom) {
            $sourceCharset = strtoupper($this->dom->_charset);
            $targetCharset = strtoupper($this->dom->_target_charset);
        }

        if (!empty($sourceCharset) && !empty($targetCharset) && (strcasecmp($sourceCharset, $targetCharset) != 0)) {
            // Check if the reported encoding could have been incorrect and the text is actually already UTF-8
            if (strcasecmp($targetCharset, 'UTF-8') == 0 && self::isUtf8($text)) {
                $converted_text = $text;
            }
            else {
                $converted_text = iconv($sourceCharset, $targetCharset, $text);
            }
        }

        // Lets make sure that we don't have that silly BOM issue with any of the utf-8 text we output.
        if ($targetCharset == 'UTF-8') {
            if (substr($converted_text, 0, 3) == "\xef\xbb\xbf") {
                $converted_text = substr($converted_text, 3);
            }
            if (substr($converted_text, -3) == "\xef\xbb\xbf") {
                $converted_text = substr($converted_text, 0, -3);
            }
        }

        return $converted_text;
    }

    /**
     * Returns true if $string is valid UTF-8 and false otherwise.
     *
     * @param mixed $str String to be tested
     * @return boolean
     */
    private static function isUtf8($str)
    {
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $c = ord($str[$i]);
            if ($c > 128) {
                if (($c >= 254)) return false;
                elseif ($c >= 252) $bits = 6;
                elseif ($c >= 248) $bits = 5;
                elseif ($c >= 240) $bits = 4;
                elseif ($c >= 224) $bits = 3;
                elseif ($c >= 192) $bits = 2;
                else return false;
                if (($i + $bits) > $len) return false;
                while ($bits > 1) {
                    $i++;
                    $b = ord($str[$i]);
                    if ($b < 128 || $b > 191) return false;
                    $bits--;
                }
            }
        }
        return true;
    }
}