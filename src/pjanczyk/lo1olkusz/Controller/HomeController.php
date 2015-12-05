<?php

namespace pjanczyk\lo1olkusz\Controller;


use pjanczyk\Framework\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->includeTemplate('home');
    }
}