<?php


namespace Aecxms\Http;


use Aecxms\Exception\CmsException;

class Response
{
    private array $httpStatus = [
        "404" => "HTTP/1.1 404 Not Found",
        "500" => "HTTP/1.1 500 Internal Server Error"
    ];

    public function setContent()
    {

    }

    /**
     * @param int $statusCode
     * @throws CmsException
     */
    public function setStatusCode(int $statusCode)
    {
        if (!array_key_exists($statusCode, $this->httpStatus)) {
            throw new CmsException(sprintf('The status set is incorrect, make sure to check that the ones available in %s in the httpStatus array', basename(__FILE__)));
        }
        header($this->httpStatus[$statusCode]);
    }

    public function send()
    {

    }
}