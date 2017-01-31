<?php

namespace EasyAdwords;


use EasyAdwords\Auth\AdWordsAuth;
use Google\AdsApi\AdWords\AdWordsServices;

class Entity extends Base {

    protected $authObject;
    protected $adWordsServices;

    public function __construct() {

        $this->adWordsServices = new AdWordsServices();

        // Create the auth object.
        $this->authObject = new AdWordsAuth($this->config->getRefreshToken(), $this->config->getAdwordsConfigPath());

        // Build the session with the auth object.
        $this->authObject->buildSession($this->config->getClientCustomerId());
    }
}