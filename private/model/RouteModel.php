<?php

namespace App\Model;

class RouteModel extends AbstractModel
{
    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->select('*', 'routes', [
            'WHERE' => [
                'name' => 'testb',
            ]
        ]);
    }
}