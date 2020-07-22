<?php

namespace App\Model;

class RouteModel extends AbstractModel
{
    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->select('*', 'routes', [
            'WHERE' => [
                'name' => 'testb',
            ]
        ]);
    }
}