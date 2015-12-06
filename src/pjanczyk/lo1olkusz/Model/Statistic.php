<?php

namespace pjanczyk\lo1olkusz\Model;


class Statistic
{
    /** @var int */
    public $pageId;
    /** @var string */
    public $date;
    /** @var int */
    public $version;
    /** @var int */
    public $visits;
    /** @var int */
    public $uniqueVisits;

    public function __construct()
    {
        settype($this->pageId, 'int');
        settype($this->version, 'int');
        settype($this->visits, 'int');
        settype($this->uniqueVisits, 'int');
    }
}