<?php

include __DIR__. '/vendor/autoload.php';

if ( ! defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__);
    define('APP_PATH', ROOT_PATH. '/app');
    define('STORAGE_PATH', ROOT_PATH. '/storage');
}

$app = \Wpt\Application::getInstance();
$app->start();
