<?php
/**
 * Copyright (C) 2016  Piotr Janczyk
 *
 * This file is part of lo1olkusz unofficial app - website.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace pjanczyk\lo1olkusz\Controller;

use pjanczyk\lo1olkusz\Auth;
use pjanczyk\lo1olkusz\Config;
use pjanczyk\lo1olkusz\Cron\CronTask;
use pjanczyk\lo1olkusz\Json;
use pjanczyk\lo1olkusz\Model\Bells;
use pjanczyk\lo1olkusz\DAO\BellsRepository;
use pjanczyk\lo1olkusz\DAO\LuckyNumberRepository;
use pjanczyk\lo1olkusz\Service\NewsService;
use pjanczyk\lo1olkusz\DAO\ReplacementsRepository;
use pjanczyk\lo1olkusz\Model\Timetable;
use pjanczyk\lo1olkusz\DAO\TimetableRepository;
use pjanczyk\lo1olkusz\Statistics\StatisticsApi;

class ApiController extends Controller
{
    public function __construct()
    {
        function getParameter($name, $defaultValue)
        {
            if (isset($_GET[$name])) {
                return urldecode($_GET[$name]);
            }
            else {
                return $defaultValue;
            }
        }

        $version = intval(getParameter('v', '0'));
        $aid = getParameter('aid', '');

        $statistics = new StatisticsApi;
        $statistics->increaseCounter(date('Y-m-d'), $version, $aid);
    }

    // GET api/news
    public function GET_news_0()
    {
        $this->GET_news_1(0);
    }

    // GET api/news/<lastModified>
    public function GET_news_1($lastModified)
    {
        $lastModified = intval($lastModified);

        $t3daysAgo = time() - 3 * 24 * 60 * 60;
        $date = date('Y-m-d', $t3daysAgo);

        $newsService = new NewsService;
        $news = $newsService->getNews($date, $lastModified);

        Json::OK($news);
    }

    // GET api/bells
    public function GET_bells_0()
    {
        $repo = new BellsRepository;
        Json::OK($repo->get());
    }

    // PUT api/bells
    public function PUT_bells_0()
    {
        if (!Auth::isAuthenticated()) {
            Json::unauthorized();
        }

        $value = json_decode(file_get_contents("php://input"));
        $error = Bells::validateValue($value);
        if ($error !== null) {
            Json::badRequest($error);
        }

        $repo = new BellsRepository;
        if ($repo->set($value)) {
            Json::OK(['message' => 'Updated']);
        }
        else {
            Json::OK(['message' => 'Same value is already set']);
        }
    }

    // GET api/timetables
    public function GET_timetables_0()
    {
        $repo = new TimetableRepository;
        Json::OK($repo->listAll());
    }

    // GET api/timetables/<class>
    public function GET_timetables_1($class)
    {
        $repo = new TimetableRepository;
        $result = $repo->getByClass($class);

        if ($result !== null) {
            Json::OK($result);
        }
        else {
            Json::notFound();
        }
    }

    // DELETE api/timetables/<class>
    public function DELETE_timetables_1($class)
    {
        if (!Auth::isAuthenticated()) {
            Json::unauthorized();
        }

        $repo = new TimetableRepository;

        if ($repo->delete($class)) {
            Json::OK(['message' => 'Deleted']);
        }
        else {
            Json::notFound();
        }

    }

    // PUT api/timetables/<class>
    public function PUT_timetables_1($class)
    {
        if (!Auth::isAuthenticated()) {
            Json::unauthorized();
        }

        $value = json_decode(file_get_contents('php://input'));
        $error = Timetable::validateValue($value);
        if ($error !== null) {
            Json::badRequest($error);
        }

        $repo = new TimetableRepository;
        if ($repo->setValue($class, $value)) {
            Json::OK(['message' => 'Saved']);
        }
        else {
            Json::internalServerError();
        }
    }

    // GET api/replacements
    public function GET_replacements_0()
    {
        $repo = new ReplacementsRepository;
        Json::OK($repo->listAll());
    }

    // GET api/replacements/<date>/<class>
    public function GET_replacements_2($date, $class)
    {
        $repo = new ReplacementsRepository;
        $result = $repo->getByClassAndDate($class, $date);

        if ($result !== null) {
            Json::OK($result);
        }
        else {
            Json::notFound();
        }
    }

    // GET api/lucky-numbers
    public function GET_lucky_numbers_0()
    {
        $repo = new LuckyNumberRepository;
        Json::OK($repo->listAll());
    }

    // GET api/lucky-numbers/<date>
    public function GET_lucky_numbers_1($date)
    {
        $repo = new LuckyNumberRepository;
        $result = $repo->getByDate($date);

        if ($result !== null) {
            Json::OK($result);
        }
        else {
            Json::notFound();
        }
    }

    // GET api/logs
    public function GET_logs_0()
    {
        $path = Config::getInstance()->getLogsPath();
        header('Content-Type: text');
        header('Access-Control-Allow-Origin: *');

        readfile($path);
    }

    // DELETE api/logs
    public function DELETE_logs_0()
    {
        if (!Auth::isAuthenticated()) {
            Json::unauthorized();
        }

        $path = Config::getInstance()->getLogsPath();

        fclose(fopen($path,'w')); // truncate file

        Json::OK(['message' => 'Deleted']);
    }

    // GET api/run-cron
    public function GET_run_cron_0()
    {
        Auth::forceLoggingIn();

        header('Content-Type: text');
        header('Access-Control-Allow-Origin: *');
        $task = new CronTask;
        $task->run();
    }
}