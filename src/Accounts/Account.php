<?php

namespace EasyAdWords\Accounts;

use EasyAdWords\Entity;
use Google\AdsApi\AdWords\v201705\mcm\ManagedCustomerService;

class Account extends Entity {

    protected $config;
    protected $accountService;
    protected $accounts;

    public function __construct(AccountConfig $config) {
        parent::__construct($config);

        $this->config = $config;

        // Build the account service.
        $this->accountService = $this->adWordsServices->get($this->authObject->getSession(), ManagedCustomerService::class);
        $this->accounts = NULL;
    }

    /**
     * List all the accounts under the manager account with the given fields and predicates.
     * Works as an alias of "getAccounts" if the list is already downloaded before.
     * @return null
     */
    public function get() {
        if (!$this->accounts) {
            $this->accounts = $this->downloadFromGoogle($this->config, $this->accountService);
        }

        return $this->accounts;
    }

    /**
     * @return AccountConfig
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * @param AccountConfig $config
     * @return Account
     */
    public function setConfig($config) {
        $this->config = $config;
        return $this;
    }

    /**
     * @return \Google\AdsApi\Common\AdsSoapClient|\Google\AdsApi\Common\SoapClient
     */
    public function getAccountService() {
        return $this->accountService;
    }

    /**
     * @param \Google\AdsApi\Common\AdsSoapClient|\Google\AdsApi\Common\SoapClient $accountService
     * @return Account
     */
    public function setAccountService($accountService) {
        $this->accountService = $accountService;
        return $this;
    }

    /**
     * @return null
     */
    public function getAccounts() {
        return $this->accounts;
    }

    /**
     * @param null $accounts
     * @return Account
     */
    public function setAccounts($accounts) {
        $this->accounts = $accounts;
        return $this;
    }
}