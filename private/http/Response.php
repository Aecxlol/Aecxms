<?php


namespace Aecxms\Http;


use Aecxms\Controller\DefaultController;
use Aecxms\Exception\CmsException;
use Aecxms\Service\DI;

/**
 * Class Response
 * @package Aecxms\Http
 */
class Response
{
    /**
     * @var DefaultController|mixed
     */
    private DefaultController $defaultController;

    /**
     * @var array
     */
    private array $httpStatus = [
        "200" => "Ok",
        "404" => "HTTP/1.1 404 Not Found",
        "500" => "HTTP/1.1 500 Internal Server Error"
    ];

    public function __construct()
    {
//        $this->defaultController = DI::getInstance()->get('Aecxms\Controller\DefaultController');
    }

    public function setContent()
    {

    }

    /**
     * @param int $statusCode
     * @throws CmsException
     */
    private function setStatusCode(int $statusCode = 200)
    {
        if (!array_key_exists($statusCode, $this->httpStatus)) {
            throw new CmsException(sprintf('The http status code set is incorrect, make sure to check that the ones available in %s in the $httpStatus array', basename(__FILE__)));
        }
        http_response_code($statusCode);
//        header($this->httpStatus[$statusCode]);
    }

    public function send()
    {

    }

    /**
     * @throws CmsException
     */
    public function redirect404()
    {
        /*
         * TODO voir pour redirect sur template 404 sans boucle infinie
         */
        $this->setStatusCode(404);
        var_dump("gege");
//        $this->defaultController->render('error/404.php');
        exit();
    }

    /**
     * Manages the error messages according to the environment
     * @param $env
     * @param $message
     * @throws CmsException
     */
    public function errorOutput($env, $message)
    {
        switch ($env) {
            case DEV_ENV:
                throw new CmsException($message);
                break;

            case PROD_ENV:
                $this->redirect404();
                break;
        }
    }
}