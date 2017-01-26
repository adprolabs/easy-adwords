<?php

namespace EasyAdwords;

class Config extends Base {

    protected $refreshToken;
    protected $clientCustomerId;
    protected $adwordsConfigPath;

    public function __construct(array $config) {
        $this->refreshToken = $config['refreshToken'];
        $this->clientCustomerId = $config['clientCustomerId'];
        $this->adwordsConfigPath = $config['adwordsConfigPath'];
    }

    /**
     * @return mixed
     */
    public function getAdwordsConfigPath() {
        return $this->adwordsConfigPath;
    }

    /**
     * @param mixed $adwordsConfigPath
     */
    public function setAdwordsConfigPath($adwordsConfigPath) {
        $this->adwordsConfigPath = $adwordsConfigPath;
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