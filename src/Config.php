<?php

namespace EasyAdwords;

class Config extends Base {

    protected $refreshToken;
    protected $clientCustomerId;
    protected $configFilePath;

    public function __construct(array $config) {
        $this->refreshToken = $config['refreshToken'];
        $this->clientCustomerId = $config['clientCustomerId'];
        $this->configFilePath = $config['configFilePath'];
    }

    /**
     * @return mixed
     */
    public function getConfigFilePath() {
        return $this->configFilePath;
    }

    /**
     * @param mixed $configFilePath
     */
    public function setConfigFilePath($configFilePath) {
        $this->configFilePath = $configFilePath;
    }

    /**
     * @return mixed
     */
    public function getClientCustomerId() {
        return $this->clientCustomerId;
    }

    /**
     * @param mixed $clientCustomerId
     */
    public function setClientCustomerId($clientCustomerId) {
        $this->clientCustomerId = $clientCustomerId;
    }

    /**
     * @return mixed
     */
    public function getRefreshToken() {
        return $this->refreshToken;
    }

    /**
     * @param mixed $refreshToken
     */
    public function setRefreshToken($refreshToken) {
        $this->refreshToken = $refreshToken;
    }

}