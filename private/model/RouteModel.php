<?php

namespace App\Model;

class RouteModel extends AbstractModel
{
    public function getRoutes()
    {
        return $this->select('*', 'routes');
    }
}