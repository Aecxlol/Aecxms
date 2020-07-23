<?php

namespace Aecxms\Model;

class RouteModel extends AbstractModel
{
    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->select('*', 'routes');
    }
}