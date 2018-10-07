<?php
/**
 * Created by PhpStorm.
 * User: jonni
 * Date: 01.12.17
 * Time: 20:10
 */
use core\Router;
//phpinfo();
define('ROOT', dirname(__FILE__));
define('VIEWS_BASEDIR', dirname(__FILE__).'/application/views/');
define('APP', dirname(__FILE__).'/application');
define('CORE', dirname(__FILE__).'/core');
require_once(ROOT.'/autoload.php');

// подключаем конфигурацию URL
$routes= include (ROOT.'/config/config_router.php');

// запускаем роутер
$router = new Router($routes);
$router->run();