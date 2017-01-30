<?php

namespace EasyAdwords;

class Config extends Base {

    protected $refreshToken;
    protected $clientCustomerId;
    protected $adwordsConfigPath;
    protected $fields;
    protected $predicates;
    protected $ordering;

    public function __construct(array $config) {

        $this->refreshToken = NULL;
        $this->clientCustomerId = NULL;
        $this->adwordsConfigPath = NULL;
        $this->ordering = NULL;

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

        if (isset($config['fields'])) {
            $this->fields = $config['fields'];
        }

        if (isset($config['predicates'])) {
            if (!is_array($config['predicates'])) {
                $config['predicates'] = [$config['predicates']];
            }

            $this->predicates = $config['predicates'];
        }

        if (isset($config['ordering'])) {
            if (!is_array($config['ordering'])) {
                $config['ordering'] = [$config['ordering']];
            }

            $this->predicates = $config['ordering'];
        }
    }


    /**
     * @return mixed
     */
    public function getRefreshToken() {
        return $this->refreshToken;
    }

    /**
     * @param mixed $refreshToken
     * @return Config
     */
    public function setRefreshToken($refreshToken) {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getClientCustomerId() {
        return $this->clientCustomerId;
    }

    /**
     * @param mixed $clientCustomerId
     * @return Config
     */
    public function setClientCustomerId($clientCustomerId) {
        $this->clientCustomerId = $clientCustomerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdwordsConfigPath() {
        return $this->adwordsConfigPath;
    }

    /**
     * @param mixed $adwordsConfigPath
     * @return Config
     */
    public function setAdwordsConfigPath($adwordsConfigPath) {
        $this->adwordsConfigPath = $adwordsConfigPath;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * @param mixed $fields
     * @return Config
     */
    public function setFields($fields) {
        $this->fields = $fields;
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getPredicates() {
        return $this->predicates;
    }

    /**
     * @param array|mixed $predicates
     * @return Config
     */
    public function setPredicates($predicates) {
        $this->predicates = $predicates;
        return $this;
    }

    /**
     * @param $predicate
     */
    public function addPredicate($predicate) {
        $this->predicates[] = $predicate;
    }

    /**
     * @return mixed
     */
    public function getOrdering() {
        return $this->ordering;
    }

    /**
     * @param mixed $ordering
     * @return Config
     */
    public function setOrdering($ordering) {
        $this->ordering = $ordering;
        return $this;
    }
}