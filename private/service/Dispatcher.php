<?php


namespace Aecxms\Service;

use Aecxms\Exception\CmsException;
use Aecxms\Http\Request;
use Aecxms\Model\RouteModel;

class Dispatcher
{
    private const CONTROLLER_NAME = 'controller_name';

    private const CONTROLLER_ACTION = 'controller_action';

    /**
     * @var Request|mixed
     */
    private Request $request;

    /**
     * @var Router|mixed
     */
    private Router $router;

    /**
     * @var RouteModel|mixed
     */
    private RouteModel $routeModel;

    /**
     * @var array
     */
    private array $dbRoutes;

    /**
     * @var object|null
     */
    private ?object $controller = null;

    /**
     * @var string
     */
    private string $env;

    /**
     * Dispatcher constructor.
     * @throws CmsException
     */
    public function __construct()
    {
        $this->request    = DI::getInstance()->get('Aecxms\Http\Request');
        $this->router     = DI::getInstance()->get('Aecxms\Service\Router');
        $this->routeModel = DI::getInstance()->get('Aecxms\Model\RouteModel');
        $this->dbRoutes   = $this->routeModel->getRoutes();
        $this->env        = Config::getEnv();

        $this->router->parseUrl($this->request);
        $this->loadController();
    }

    /**
     * Verify if the url is in the right format (domain/controller/action/params)
     * @return bool
     */
    private function isUrlFormatValid()
    {
        if (!$this->router->getController() || !$this->router->getAction()) {
            return false;
        }
        return true;
    }

    /**
     * Verify if the controller's name specified in the url exists
     * if so, get an instance of this controller.
     * Files wise
     * @return bool
     */
    private function doesControllerExist()
    {
        $controllerDir = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'controller';
        $controller    = ucfirst($this->router->getController()) . 'Controller';
        if (!file_exists($controllerDir . DIRECTORY_SEPARATOR . $controller . '.php')) {
            return false;
        } else {
            $controller       = 'Aecxms\\Controller\\' . $controller;
            $this->controller = DI::getInstance()->get($controller);
            return true;
        }
    }

    /**
     * Verify if the action provided in the url exists in the controller provided
     * @return bool
     */
    private function doesActionExist()
    {
        if ($this->doesControllerExist()) {
            if (!method_exists($this->controller, $this->router->getAction())) {
                return false;
            }
        }
        return true;
    }

    /**
     * Load the controller 'called' in the url only if it matches with one's in the DB
     * @throws CmsException
     */
    private function loadController()
    {
        if ($this->isUrlFormatValid() && $this->doesActionExist()) {
            foreach ($this->dbRoutes as $route) {
                $controller = ucfirst($this->router->getController()) . 'Controller';
                // Verify if the controller provided in the url exists in the DB
                if ($route[self::CONTROLLER_NAME] === $controller) {
                    // Verify if the action provided in the url exists in the DB
                    if ($route[self::CONTROLLER_ACTION] === $this->router->getAction()) {
                        $action = $this->router->getAction();
                        $this->controller->$action();
                    } else {
                        $this->errorOutput($this->env, sprintf('The action %s does not exist in the database.', $this->router->getAction()));
                    }
                } else {
                    $this->errorOutput($this->env, sprintf('The controller %s does not exist in the database.', $controller));
                }
                break;
            }
        } else {
            $this->errorOutput($this->env, sprintf('Error : something went wrong for multiple possible reasons. Please check the url\'s format, the controller or the action provided in the url.'));
        }
    }

    /**
     * Redirect to 404
     */
    public static function redirect404()
    {
        header('HTTP/1.1 404 Not Found');
        $controller = DI::getInstance()->get('Aecxms\Controller\HomeController');
        $controller->render('error/404.php');
        exit();
    }

    /**
     * Manages the error messages according to the environment
     * @param $env
     * @param $message
     * @throws CmsException
     */
    public static function errorOutput($env, $message)
    {
        switch ($env) {
            case DEV_ENV:
                throw new CmsException($message);
                break;

            case PROD_ENV:
                self::redirect404();
                break;
        }
    }
}