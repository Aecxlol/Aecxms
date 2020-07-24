<?php


namespace Aecxms\Controller;


abstract class AbstractController
{
    public function render(string $view, array $params = [])
    {
        require(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . $view);
    }
}