<?php

namespace pjanczyk\lo1olkusz\Controller;

use pjanczyk\framework\Controller;
use pjanczyk\lo1olkusz\Json;
use pjanczyk\lo1olkusz\Model\LuckyNumberRepository;
use pjanczyk\lo1olkusz\Model\News;
use pjanczyk\lo1olkusz\Model\ReplacementsRepository;
use pjanczyk\lo1olkusz\Model\SettingRepository;
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

        $page = getParameter('p', null);
        $version = intval(getParameter('v', '0'));
        $androidId = getParameter('aid', '0');
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
        $now = time();

        $today = date('Y-m-d', $now);

        $replacements = new ReplacementsRepository;
        $luckyNumbers = new LuckyNumberRepository;
        $timetables = new TimetableRepository;
        $settings = new SettingRepository;

        $news = new News;

        $news->timetables = $now;
        $news->replacements = $replacements->getByDateAndLastModified($today, $lastModified);
        $news->luckyNumbers = $luckyNumbers->getByDateAndLastModified($today, $lastModified);
        $news->timetables = $timetables->getByLastModified($lastModified);
        $news->version = (int) $settings->get('version');

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
        include 'api/cron.php';
    }
}