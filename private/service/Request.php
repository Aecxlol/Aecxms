<?php


namespace Aecxms\Service;


class Request
{
    /**
     * @var string
     */
    private string $url;

    /**
     * @var string
     */
    private string $requestMethod;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->url = $_SERVER['REQUEST_URI'];
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    private function setUrl(string $url): void
    {
        $this->url = $url;
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