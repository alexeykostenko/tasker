<?php

define('APP_PATH', dirname(__DIR__));

require_once APP_PATH . "/vendor/autoload.php";

$app = require_once APP_PATH . '/app.php';
$app->start();
