<?php

use Aecxms\Service\DI;

define('PRIVATE_DIR_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR);
define('DEV_ENV', 'dev');
define('PROD_ENV', 'prod');

require PRIVATE_DIR_PATH . 'vendor/Autoloader.php';

Autoloader::register();

DI::getInstance()->get('Dispatcher');