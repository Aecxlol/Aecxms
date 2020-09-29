<?php


namespace Aecxms\Controller;


use Aecxms\Exception\CmsException;
use Aecxms\Http\Response;
use Aecxms\Service\Config;
use Aecxms\Service\DI;


/**
 * Class AbstractController
 * @package Aecxms\Controller
 */
abstract class AbstractController
{
    private const LAYOUT = 'layout.php';

    /**
     * @var string
     */
    private string $viewDirectory;

    /**
     * @var Response
     */
    private Response $response;

    /**
     * @var string
     */
    private string $env;

    /**
     * AbstractController constructor.
     */
    public function __construct()
    {
        $this->setViewDirectory(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR);
        $this->response = DI::getInstance()->get('Response');
        $this->env      = Config::getEnv();
    }

    /**
     * @param string $view
     * @param array $params
     * @throws CmsException
     */
    private function loadTemplate(string $view, array $params = [])
    {
        $file   = $this->viewDirectory . $view;
        $layout = $this->viewDirectory . self::LAYOUT;

        if (file_exists($file)) {
            /**
             * TODO voir pour avoir les params dispo dans le template fils
             */
            ob_start();
            require($file);
            if(!empty($params)) {
                extract($params);
            }
            $content = ob_get_clean();
            if (file_exists($layout)) {
                require($layout);
            } else {
                $this->response->errorOutput($this->env, sprintf('The file %s does not exist in %s', self::LAYOUT, $layout));
            }
        } else {
            $this->response->errorOutput($this->env, sprintf('The file %s does not exist in %s', $view, $file));
        }
    }

    /**
     * @param string $view
     * @param array $params
     */
    public function render(string $view, array $params = [])
    {
        try {
            $this->loadTemplate($view, $params);
        } catch (CmsException $e) {
            $errorMessage  = $e->getMessage();
            $errorTemplate = $this->viewDirectory . 'error' . DIRECTORY_SEPARATOR . 'errorView.php';
            require($errorTemplate);
        }
    }

    /**
     * @param string $viewDirectory
     */
    private function setViewDirectory(string $viewDirectory)
    {
        $this->viewDirectory = $viewDirectory;
    }
}