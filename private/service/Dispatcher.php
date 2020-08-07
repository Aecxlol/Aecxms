<?php


namespace Aecxms\Service;


use Aecxms\Controller\AbstractController;
use Aecxms\Exception\CmsException;
use Aecxms\Model\RouteModel;

class Dispatcher
{
    private const CONTROLLER_NAME = 'controller_name';

    private const CONTROLLER_ACTION = 'controller_action';

    private const DEV_ENV = "dev";

    private const PROD_ENV = "prod";

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
     * @var Config
     */
    private Config $config;

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
        $this->request    = DI::getInstance()->get('Aecxms\Service\Request');
        $this->router     = DI::getInstance()->get('Aecxms\Service\Router');
        $this->routeModel = DI::getInstance()->get('Aecxms\Model\RouteModel');
        $this->dbRoutes   = $this->routeModel->getRoutes();
        $this->env        = Config::getEnv();

        $this->router->parseUrl($this->request);
        $this->loadController();
    }

    /**
     * @return bool
     * Verify if the url is in the right format (domain/controller/action/params)
     */
    private function isUrlFormatValid()
    {
        if (!$this->router->getController() || !$this->router->getAction()) {
            return false;
        }
        return true;
    }

    /**
     * @return bool
     * Verify if the controller's name specified in the url exists
     * if so, get an instance of this controller.
     * Files wise
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
     * @return bool
     * Verify if the action provided in the url exists in the controller provided
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
     * @throws CmsException
     * Load the controller 'called' in the url only if it matches with one's in the DB
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
                        if ($this->env === self::DEV_ENV) {
                            throw new CmsException(sprintf('The action %s does not exist in the database.', $this->router->getAction()));
                        } else {
                            $this->redirect404();
                        }
                    }
                } else {
                    if ($this->env === self::DEV_ENV) {
                        throw new CmsException(sprintf('The controller %s does not exist in the database.', $controller));
                    } else {
                        $this->redirect404();
                    }
                }
                break;
            }
        } else {
            if ($this->env === self::DEV_ENV) {
                throw new CmsException(sprintf('Error : something went wrong for multiple possible reasons. Please check the url\'s format, the controller or the action provided in the url.'));
            } else {
                $this->redirect404();
            }
        }
    }

    public function redirect404()
    {
        header('HTTP/1.0 404 Not Found');
        $controller = DI::getInstance()->get('Aecxms\Controller\HomeController');
        $controller->render('error/404.php');
        exit();
    }
}