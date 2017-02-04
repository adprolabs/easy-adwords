<?php

namespace EasyAdWords\Auth;

use Google\AdsApi\AdWords\AdWordsSession;
use Google\AdsApi\AdWords\AdWordsSessionBuilder;
use Google\AdsApi\Common\OAuth2TokenBuilder;
use Google\Auth\OAuth2;

/**
 * Wrapper class for oAuth2 operations.
 *
 * Class AdWordsAuth
 * @package EasyAdWords\Auth
 */
class AdWordsAuth {

    /**
     * @var string                  Refresh token of the client to authenticate - required.
     */
    protected $refreshToken;

    /**
     * @var string                  The path of the config file for the AdWords Client library - required.
     * If not given, the package looks for "adsapi_php.ini" config file in the root of the project.
     */
    protected $configFilePath;

    /**
     * @var Google\Auth\Credentials\UserRefreshCredentials The built oAuth object.
     */
    protected $oAuthCredentials;

    /**
     * @var AdWordsSession          The session variable that is built from the given oAuth credentials.
     */
    protected $session;

    /**
     * AdWordsAuth constructor.
     * @param null $refreshToken
     * @param null $configFilePath
     */
    public function __construct($refreshToken = NULL, $configFilePath = NULL) {
        $this->refreshToken = $refreshToken;
        $this->configFilePath = $configFilePath;

        if($refreshToken) {
            $this->buildOAuthToken();
        }
    }

    /**
     * Get refresh token.
     * @return mixed
     */
    public function getRefreshToken() {
        return $this->refreshToken;
    }

    /**
     * Set refresh token.
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
     * Get oAuth credentials.
     * @return mixed
     */
    public function getOAuthCredentials() {
        return $this->oAuthCredentials;
    }

    /**
     * Set oAuth credentials.
     * @param $oAuthCredentials
     * @return $this
     */
    public function setOAuthCredentials($oAuthCredentials) {
        $this->oAuthCredentials = $oAuthCredentials;
        return $this;
    }

    /**
     * Get AdWords session.
     * @return mixed
     */
    public function getSession() {
        return $this->session;
    }

    /**
     * Set AdWords session.
     * @param AdWordsSession $session
     * @return $this
     */
    public function setSession(AdWordsSession $session) {
        $this->session = $session;
        return $this;
    }
}