<?php


namespace Aecxms\Http;


use Aecxms\Exception\CmsException;
use Aecxms\Service\Config;

/**
 * Class Response
 * @package Aecxms\Http
 */
class Response
{
    /**
     * Response constructor.
     */
    public function __construct()
    {
    }

    public function setContent()
    {

    }

    /**
     * @param int $statusCode
     */
    private function setStatusCode(int $statusCode = 200)
    {
        http_response_code($statusCode);
    }

    public function send()
    {

    }

    public function redirect404()
    {
        /**
         * @todo voir pour redirect sur template 404 sans boucle infinie
         **/
        $this->setStatusCode(404);
//        $this->render('error/404.php');
//        exit();
    }

    /**
     * Manages the error messages according to the environment
     * @param $message
     * @throws CmsException
     */
    public function errorOutput($message)
    {
        switch (Config::getEnv()) {
            case DEV_ENV:
                throw new CmsException($message);
                break;

            case PROD_ENV:
                $this->redirect404();
                break;
        }
    }
}