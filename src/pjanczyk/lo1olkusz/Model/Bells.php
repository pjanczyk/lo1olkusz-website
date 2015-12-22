<?php

namespace pjanczyk\lo1olkusz\Model;


class Bells
{
    public $lastModified;
    /** @var string */
    public $value;

    public function __construct()
    {
        settype($this->lastModified, 'int');
        $this->value = json_decode($this->value, true);
    }
}