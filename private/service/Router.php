<?php


namespace App\Service;


class Router
{
    public function __construct()
    {
        DI::getInstance()->get('App\Model\AbstractModel');
    }
}