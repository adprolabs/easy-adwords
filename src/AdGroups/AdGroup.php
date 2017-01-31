<?php

namespace EasyAdwords\AdGroups;

use EasyAdwords\Entity;
use EasyAdwords\EntityInterface;
use Exception;
use Google\AdsApi\AdWords\v201609\cm\AdGroupOperation;
use Google\AdsApi\AdWords\v201609\cm\AdGroupService;
use Google\AdsApi\AdWords\v201609\cm\AdGroupStatus;
use Google\AdsApi\AdWords\v201609\cm\BiddingStrategyConfiguration;
use Google\AdsApi\AdWords\v201609\cm\CpcBid;
use Google\AdsApi\AdWords\v201609\cm\Money;
use Google\AdsApi\AdWords\v201609\cm\Operator;
use Google\AdsApi\AdWords\v201609\cm\Selector;


class AdGroup extends Entity implements EntityInterface {

    protected $config;
    protected $adGroups;
    protected $adGroupService;
    protected $adGroupObject;
    protected $operationResult;

    public function __construct(AdGroupConfig $config) {
        parent::__construct();
        $this->config = $config;

        // Build the campaign service.
        $this->adGroupService = $this->adWordsServices->get($this->authObject->getSession(), AdGroupService::class);
        $this->adGroupObject = new \Google\AdsApi\AdWords\v201609\cm\AdGroup();
        $this->adGroups = NULL;
    }

    /**
     * Create an ad group based on given config information.
     * @throws Exception
     */
    public function create() {

        if (!$this->config->getAdGroupName()) {
            throw new Exception("AdGroup name must be set in the config object in order to create ad group.");
        }

        if (!$this->config->getBid()) {
            throw new Exception("Bid amount must be set in the config object in order to create ad group.");
        }

        // Create an ad group with required settings and specified status.
        $this->adGroupObject->setCampaignId($this->config->getCampaignId());
        $this->adGroupObject->setName($this->config->getAdGroupName());
        $this->adGroupObject->setStatus($this->config->getStatus());

        // Set bids.
        $bid = new CpcBid();
        $money = new Money();
        $money->setMicroAmount($this->config->getBid() * 1000000);
        $bid->setBid($money);
        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBids([$bid]);
        $this->adGroupObject->setBiddingStrategyConfiguration($biddingStrategyConfiguration);

        // Create an ad group operation and add it to the operations list.
        $operation = new AdGroupOperation();
        $operation->setOperand($this->adGroupObject);
        $operation->setOperator(Operator::ADD);

        // Create the ad groups on the server.
        $result = $this->adGroupService->mutate([$operation]);
        $this->operationResult = $result->getValue();
    }

    /**
     * List all the ad groups with the given fields and predicates.
     * Works as an alias of "getAdGroups" if the list is already downloaded before.
     * @return null
     */
    public function get() {
        if (!$this->adGroups) {
            $this->downloadFromGoogle();
        }

        return $this->adGroups;
    }

    /**
     * Download all the ad groups that meet the given config criteria.
     * Useful if the list needs to be re-downloaded.
     */
    public function downloadFromGoogle() {

        // Create a selector to select all ad groups for the specified campaign.
        $selector = new Selector();
        $selector->setFields($this->config->getFields());
        $selector->setOrdering($this->config->getOrdering());
        $selector->setPredicates($this->config->getPredicates());

        $adGroups = $this->adGroupService->get($selector);
        $this->adGroups = $adGroups->getEntries();
    }

    /**
     * Remove an ad group given its ad group ID.
     * @throws Exception
     */
    public function remove() {

        if (!$this->config->getAdGroupId()) {
            throw new Exception("Ad group ID must be set in the config object in order to remove an ad group.");
        }

        // Create ad group with REMOVED status.
        $this->adGroupObject->setId($this->config->getAdGroupId());
        $this->adGroupObject->setStatus(AdGroupStatus::REMOVED);

        // Create ad group operation and add it to the list.
        $operation = new AdGroupOperation();
        $operation->setOperand($this->adGroupObject);
        $operation->setOperator(Operator::SET);

        // Remove the ad group on the server.
        $result = $this->adGroupService->mutate([$operation]);
        $this->operationResult = $result->getValue()[0];
    }


    /**
     * @return AdGroupConfig
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * @param AdGroupConfig $config
     * @return AdGroup
     */
    public function setConfig($config) {
        $this->config = $config;
        return $this;
    }

    /**
     * @return null
     */
    public function getAdGroups() {
        return $this->adGroups;
    }

    /**
     * @param null $adGroups
     * @return AdGroup
     */
    public function setAdGroups($adGroups) {
        $this->adGroups = $adGroups;
        return $this;
    }

    /**
     * @return \Google\AdsApi\Common\AdsSoapClient|\Google\AdsApi\Common\SoapClient
     */
    public function getAdGroupService() {
        return $this->adGroupService;
    }

    /**
     * @param \Google\AdsApi\Common\AdsSoapClient|\Google\AdsApi\Common\SoapClient $adGroupService
     * @return AdGroup
     */
    public function setAdGroupService($adGroupService) {
        $this->adGroupService = $adGroupService;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationResult() {
        return $this->operationResult;
    }

    /**
     * @param mixed $operationResult
     * @return AdGroup
     */
    public function setOperationResult($operationResult) {
        $this->operationResult = $operationResult;
        return $this;
    }
}