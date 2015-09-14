<?php

namespace pjanczyk\framework;


final class Application
{
    /** @var Application|null */
    private static $instance = null;

    private $config;
    private $page;
    private $pageName;
    private $action;
    private $params;

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return Controller
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    public function start(Config $config)
    {
        $this->config = $config;

        if (!$this->route($config->getPagesMap())) {
            http404();
        }

        $db = new Database($config);

        $this->page = new $this->pageName($db);

        if (!method_exists($this->page, $this->action)) {
            http404();
        }

        call_user_func_array([$this->page, $this->action], $this->params);
    }

    private function route($map)
    {
        $path = isset($_GET['p']) ? $_GET['p'] : ''; //default path: ""
        $path = trim($path, '/');
        $path = filter_var($path, FILTER_SANITIZE_URL);
        $path = explode('/', $path);

        $mapKey = $path[0];
        if (isset($map[$mapKey])) {
            $this->pageName = $map[$mapKey];
            $this->action = isset($path[1]) ? str_replace('-', '_', $path[1]) : 'index';
            $this->params = array_slice($path, 2);

            return true;
        }
        return false;
    }
}