<?php


namespace App\Service;


class Router
{
    public function __construct()
    {
        $routes = DI::getInstance()->get('App\Model\RouteModel');
//        var_dump($routes->getRoutes());
    }
}