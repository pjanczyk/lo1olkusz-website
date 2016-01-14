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
        $this->value = json_decode($this->value);
    }

    public static function validateValue($value)
    {
        if (!is_array($value)) return '"value" must be an array';
        foreach ($value as $member) {
            if (!is_array($member) || count($member) !== 2 || !is_string($member[0]) || !is_string($member[1]))
                return 'Each member of the array "value" must be an array containing 2 strings';
        }
        return null;
    }
}