<?php

namespace PiotrJanczyk\lo1olkusz\Controller;


use PiotrJanczyk\Framework\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->includeTemplate('home');
    }
}