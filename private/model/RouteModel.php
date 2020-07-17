<?php

namespace App\Model;

class RouteModel extends AbstractModel
{
    public function getRoutes()
    {
        $routes = $this->select('*', 'routes');
        $routes->execute();
        $routes->fetch();
    }
}