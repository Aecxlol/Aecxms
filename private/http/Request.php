<?php


namespace Aecxms\Http;


/**
 * Class Request
 * @package Aecxms\Http
 */
class Request
{
    /**
     * @var string
     */
    private string $pathInfo;

    /**
     * @var string
     */
    private string $requestMethod;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->pathInfo      = $_SERVER['REQUEST_URI'];
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return string
     */
    public function getPathInfo(): string
    {
        return $this->pathInfo;
    }

    /**
     * @param string $pathInfo
     */
    private function setUrl(string $pathInfo): void
    {
        $this->pathInfo = $pathInfo;
    }

    /**
     * @return string
     */
    public function getRequestMethod(): string
    {
        return $this->requestMethod;
    }

    /**
     * @param string $requestMethod
     */
    private function setRequestMethod(string $requestMethod): void
    {
        $this->requestMethod = $requestMethod;
    }
}