<?php

use Aecxms\Service\DI;

define('PRIVATE_DIR_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'private' . DIRECTORY_SEPARATOR);

require PRIVATE_DIR_PATH . 'vendor/Autoloader.php';

Autoloader::register();

DI::getInstance()->get('Aecxms\Service\Dispatcher');