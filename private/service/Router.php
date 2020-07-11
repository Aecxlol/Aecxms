<?php


namespace App\Service;


class Router
{
    public function __construct()
    {
        DI::getInstance()->get('Config');
    }
}