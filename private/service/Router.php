<?php

namespace Aecxms\Service;

use Aecxms\Model\RouteModel;

class Router
{
    /**
     * @var string
     */
    private string $currentUrl;

    /**
     * @var array
     */
    private array $existingRoutes;

    private const CONTROLLER_NAMESPACE = 'Aecxms\\Controller\\';

    public function __construct()
    {
        $routes = DI::getInstance()->get('Aecxms\Model\RouteModel');
        $this->buildCurrentUrl();
        $this->setExistingRoutes($routes->getRoutes());
        $this->getRoutes();
    }

    /**
     *
     */
    public function getRoutes()
    {
        foreach ($this->existingRoutes as $route) {
            if($this->currentUrl === $route['url']) {
                $controller = self::CONTROLLER_NAMESPACE . $route['controller_name'];
                $controller = new $controller();
                $action = $route['controller_action'];
                $controller->$action();
                break;
            }
        }
    }

    /**
     * Build the complete current URL to a string
     */
    private function buildCurrentUrl(): void
    {
        $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $this->setCurrentUrl($url);
    }

    public function redirectTo404 ()
    {
        var_dump("404 page not found");
    }

    /**
     * @return string
     */
    public function getCurrentUrl(): string
    {
        return $this->currentUrl;
    }

    /**
     * @param string $currentUrl
     */
    public function setCurrentUrl(string $currentUrl): void
    {
        $this->currentUrl = $currentUrl;
    }

    /**
     * @return array
     */
    public function getExistingRoutes(): array
    {
        return $this->existingRoutes;
    }

    /**
     * @param array $existingRoutes
     */
    public function setExistingRoutes(array $existingRoutes): void
    {
        $this->existingRoutes = $existingRoutes;
    }

}