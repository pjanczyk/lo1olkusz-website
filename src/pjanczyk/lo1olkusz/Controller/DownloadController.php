<?php

namespace pjanczyk\lo1olkusz\Controller;

use pjanczyk\Framework\Application;
use pjanczyk\Framework\Controller;
use pjanczyk\lo1olkusz\Config;
use pjanczyk\lo1olkusz\Model\SettingRepository;
use pjanczyk\lo1olkusz\Model\StatisticRepository;

class DownloadController extends Controller
{
    public function index()
    {
        $settings = new SettingRepository;
        $version = $settings->getVersion();
        $statistics = new StatisticRepository;
        $statistics->increaseVisits(StatisticRepository::DOWNLOAD, date('Y-m-d'), $version, '');

        /** @var Config $config */
        $config = Application::getConfig();
        $path = $config->getDataDir() . 'apk';

        if (file_exists($path)) {
            header('Content-Type: application/vnd.android.package-archive');
            header('Content-Disposition: attachment; filename="lo1olkusz-app.apk"');
            readfile($path);
        }
        else {
            header('HTTP/1.0 404 Not Found');
            readfile('html/404.html');
        }
    }
}