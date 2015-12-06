<?php

namespace pjanczyk\lo1olkusz\Controller;


use pjanczyk\Framework\Controller;
use pjanczyk\lo1olkusz\Model\StatisticRepository;

class HomeController extends Controller
{
    public function index()
    {
        $statistics = new StatisticRepository;
        $statistics->increaseVisits(StatisticRepository::HOME_PAGE, date('Y-m-d'), 0, '');

        $this->includeTemplate('home')->render();
    }
}