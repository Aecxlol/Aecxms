<?php

namespace Aecxms\Service;

use Aecxms\Http\Request;

class Router
{
    private const PATH = 'path';

    private const CONTROLLER = 2;

    private const ACTION = 3;

    private const PARAMS = 4;

    /**
     * @var string
     */
    private string $controller;

    /**
     * @var string
     */
    private string $action;

    /**
     * @var int
     */
    private int $params;

    /**
     * Router constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param Request $request
     */
    public function parseUrl(Request $request)
    {
        $uri = parse_url($request->getPathInfo());
        $uri = explode('/', $uri[self::PATH]);

        $this->controller = $uri[self::CONTROLLER] ?? false;
        $this->action     = $uri[self::ACTION] ?? false;
        $this->params     = $uri[self::PARAMS] ?? false;
    }

    /**
     * @return string
     */
    public function getController(): string
    {
        return $this->controller;
    }

    /**
     * @param string $controller
     */
    public function setController(string $controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @param string $action
     */
    public function setAction(string $action): void
    {
        $this->action = $action;
    }

    /**
     * @return int
     */
    public function getParams(): int
    {
        return $this->params;
    }

    /**
     * @param int $params
     */
    public function setParams(int $params): void
    {
        $this->params = $params;
    }
}