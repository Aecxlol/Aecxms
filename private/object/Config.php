<?php


namespace App\Object;


class Config
{
    /**
     * @var array
     */
    private array $configFile = [];

    public function __construct()
    {
        $this->setConfigFile(require('../private/config/Config.php'));
    }

    /**
     * @return array|mixed
     */
    public function getConfigFile()
    {
        return $this->configFile;
    }

    /**
     * @param array|mixed $configFile
     */
    public function setConfigFile($configFile): void
    {
        $this->configFile = $configFile;
    }

}