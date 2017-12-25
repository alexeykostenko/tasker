<?php

namespace Library;

class Config
{
    private $config;

    public function __construct()
    {
        $this->set();
    }

    public function set()
    {
        $this->config = include app_path('config.php');
    }

    public function all()
    {
        return $this->config;
    }

    public function get($key, $default)
    {
        if (isset($this->config[$key])) {
            return $this->config[$key];
        }

        return $default;
    }
}
