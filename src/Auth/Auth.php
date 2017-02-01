<?php

namespace EasyAdwords\Auth;

use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\Auth\OAuth2;

/**
 * Wrapper class for oAuth2 operations.
 *
 * Class AdWordsAuth
 * @package EasyAdwords\Auth
 */
class AdWordsAuth {

    protected $refreshToken;
    protected $configFilePath;
    protected $oAuthCredentials;
    protected $session;

    public function __construct($refreshToken = NULL, $configFilePath = NULL) {
        $this->refreshToken = $refreshToken;
        $this->configFilePath = $configFilePath;

        if($refreshToken) {
            $this->buildOAuthToken();
        }
    }

    /**
     * @return mixed
     */
    public function getRefreshToken() {
        return $this->refreshToken;
    }

    /**
     * Set the refresh token.
     * @param $refreshToken
     * @return $this
     */
    public function setRefreshToken($refreshToken) {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * Build the oAuth object.
     * @return $this
     */
    public function buildOAuthToken() {
        // Generate a refreshable OAuth2 credential for authentication.
        $oAuth = (new OAuth2TokenBuilder())->fromFile($this->configFilePath);

        if ($this->refreshToken) {
            $oAuth->withRefreshToken($this->refreshToken);
        }
        $this->oAuthCredentials = $oAuth->build();
        return $this;
    }

    /**
     * Build an oAuth2 object for using with stateful web-app authentication process.
     * @param array $config
     * @return OAuth2
     */
    public function buildOAuthObject(array $config){
        return new OAuth2($config);
    }

    /**
     * Build the session object with oAuth credentials.
     * @param null $clientCustomerId
     * @return \Google\AdsApi\AdWords\AdWordsSession|mixed
     */
    public function buildSession($clientCustomerId = NULL) {

        $session = (new AdWordsSessionBuilder())
            ->fromFile($this->configFilePath)
            ->withOAuth2Credential($this->oAuthCredentials);

        if($clientCustomerId) {
            $session->withClientCustomerId($clientCustomerId);
        }

        $this->session = $session->build();
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOAuthCredentials() {
        return $this->oAuthCredentials;
    }

    /**
     * @param $oAuthCredentials
     * @return $this
     */
    public function setOAuthCredentials($oAuthCredentials) {
        $this->oAuthCredentials = $oAuthCredentials;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * @param AdWordsSession $session
     * @return $this
     */
    public function setSession(AdWordsSession $session) {
        $this->session = $session;
        return $this;
    }
}