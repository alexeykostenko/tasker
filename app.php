<?php

class App
{
    public function start()
    {
        set_settings();
        session_start();
        return require_once APP_PATH . '/routes.php';
    }
}

return new App;
