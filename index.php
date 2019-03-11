<?php

// 1. Общие настройки
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// 2. Подключаем файлы
define('ROOT', dirname(__FILE__));
require_once (ROOT.'/components/Autoloader.php');
require_once (ROOT.'/components/Router.php');

// 3. Вызов Router
$router = new Router();
$router->run();
