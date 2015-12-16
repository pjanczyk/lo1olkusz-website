<?php

namespace pjanczyk\lo1olkusz\Controller;

use pjanczyk\Framework\Controller;
use pjanczyk\lo1olkusz\Config;
use pjanczyk\lo1olkusz\Cron\CronTask;
use pjanczyk\lo1olkusz\Json;
use pjanczyk\lo1olkusz\Model\LuckyNumberRepository;
use pjanczyk\lo1olkusz\Model\NewsService;
use pjanczyk\lo1olkusz\Model\ReplacementsRepository;
use pjanczyk\lo1olkusz\Model\StatisticRepository;
use pjanczyk\lo1olkusz\Model\TimetableRepository;

class RestController extends Controller
{
    public function __construct()
    {
        function getParameter($name, $defaultValue) {
            if (isset($_GET[$name])) {
                return urldecode($_GET[$name]);
            } else {
                return $defaultValue;
            }
        }

        $version = intval(getParameter('v', '0'));
        $androidId = getParameter('aid', '');

        $statistics = new StatisticRepository;
        $statistics->increaseVisits(StatisticRepository::REST_API, date('Y-m-d'), $version, $androidId);
    }

    public function index()
    {
        Json::badRequest();
    }

    // api/news/<lastModified>
    public function news()
    {
        if (func_num_args() !== 1) {
            Json::badRequest();
            return;
        }

        $lastModified = intval(func_get_arg(0));
        $today = date('Y-m-d');

        $newsService = new NewsService;
        $news = $newsService->getNews($today, $lastModified);

        Json::OK($news);
    }

    // api/timetables/[<class>]
    public function timetables()
    {
        $repo = new TimetableRepository;

        if (func_num_args() == 0) {
            $result = $repo->listAll();
            Json::OK($result);
        }
        else if (func_num_args() == 1) {
            $class = func_get_arg(0);

            $result = $repo->getByClass($class);

            if ($result !== null) {
                Json::OK($result);
            } else {
                Json::notFound();
            }
        }
        else {
            Json::badRequest();
        }
    }

    // api/replacements/[<date>/<class>]
    public function replacements()
    {
        $repo = new ReplacementsRepository;

        if (func_num_args() == 0) {
            $result = $repo->listAll();
            Json::OK($result);
        }
        else if (func_num_args() == 2) {
            $date = func_get_arg(0);
            $class = func_get_arg(1);

            $result = $repo->getByClassAndDate($class, $date);

            if ($result !== null) {
                Json::OK($result);
            } else {
                Json::notFound();
            }
        }
        else {
            Json::badRequest();
        }
    }

    // api/lucky-numbers/[<date>]
    public function lucky_numbers()
    {
        $repo = new LuckyNumberRepository;

        if (func_num_args() == 0) {
            $result = $repo->listAll();
            Json::OK($result);
        }
        else if (func_num_args() == 1) {
            $date = func_get_arg(0);

            $result = $repo->getByDate($date);

            if ($result !== null) {
                Json::OK($result);
            } else {
                Json::notFound();
            }
        }
        else {
            Json::badRequest();
        }
    }

    // api/logs
    public function logs()
    {
        $config = new Config;
        $path = $config->getLogDir() . 'cron.log';

        if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            header('Content-Type: text');
            header('Access-Control-Allow-Origin: *');
            if (file_exists($path)) {
                readfile($path);
            }
        }
        else if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
            header('Content-Type: text');
            header('Access-Control-Allow-Origin: *');
            unlink($path);
            echo 'OK';
        }
        else if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            header('Content-Type: text');
            header('Access-Control-Allow-Origin: *');
            $task = new CronTask;
            $task->run();
        }
        else {
            Json::badRequest();
        }
    }
}