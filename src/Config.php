<?php

namespace EasyAdwords;

class Config extends Base {

    protected $refreshToken;
    protected $clientCustomerId;
    protected $adwordsConfigPath;

    public function __construct(array $config) {

        $this->refreshToken = NULL;
        $this->clientCustomerId = NULL;
        $this->adwordsConfigPath = NULL;

        if (isset($config['refreshToken'])) {
            $this->refreshToken = $config['refreshToken'];
        } else {
            throw new \Exception("Refresh token must be set in config array.");
        }


        if (isset($config['clientCustomerId'])) {
            $this->clientCustomerId = $config['clientCustomerId'];
        } else {
            throw new \Exception("Client customer ID must be set in config array.");
        }

        if (isset($config['adwordsConfigPath'])) {
            $this->adwordsConfigPath = $config['adwordsConfigPath'];
        }
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