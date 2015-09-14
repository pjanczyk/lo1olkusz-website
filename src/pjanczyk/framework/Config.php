<?php

namespace pjanczyk\framework;


interface Config {
    public function getDbDSN();
    public function getDbUser();
    public function getDbPassword();
    public function getDbOptions();
    public function getRoute();
}