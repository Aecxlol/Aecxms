<?php

namespace Aecxms\Service;

class Router
{
    public function __construct()
    {
        $routes = DI::getInstance()->get('Aecxms\Model\RouteModel');
        var_dump($_SERVER);
//        $routes->getRoutes();
    }
}