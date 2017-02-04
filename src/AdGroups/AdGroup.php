<?php

namespace EasyAdWords\AdGroups;

use EasyAdWords\Entity;
use EasyAdWords\EntityInterface;
use Exception;
use Google\AdsApi\AdWords\v201609\cm\AdGroupOperation;
use Google\AdsApi\AdWords\v201609\cm\AdGroupService;
use Google\AdsApi\AdWords\v201609\cm\AdGroupStatus;
use Google\AdsApi\AdWords\v201609\cm\BiddingStrategyConfiguration;
use Google\AdsApi\AdWords\v201609\cm\CpcBid;
use Google\AdsApi\AdWords\v201609\cm\Money;
use Google\AdsApi\AdWords\v201609\cm\Operator;

/**
 * Base class for basic ad group operations.
 * Operates based on the given AdGroupConfig object.
 * Allows basic operations such as creating an ad group, listing ad groups and removing an ad group.
 *
 * Class AdGroup
 * @package EasyAdWords\AdGroups
 */
class AdGroup extends Entity implements EntityInterface {


    /**
     * @var AdGroupConfig   The config object that is needed for class to operate with.
     */
    protected $config;

    /**
     * @var null            The list of ad groups, filled with the result of the get operation.
     */
    protected $adGroups;

    /**
     * @var \Google\AdsApi\Common\AdsSoapClient|\Google\AdsApi\Common\SoapClient The ad group service - created from AdWords services.
     */
    protected $adGroupService;

    /**
     * @var \Google\AdsApi\AdWords\v201609\cm\AdGroup The ad group object that the class will operate with.
     */
    protected $adGroupObject;

    /**
     * AdGroup constructor.
     * @param AdGroupConfig $config
     */
    public function __construct(AdGroupConfig $config) {
        parent::__construct($config);

        $this->config = $config;

        // Build the ad group service.
        $this->adGroupService = $this->adWordsServices->get($this->authObject->getSession(), AdGroupService::class);
        $this->adGroupObject = new \Google\AdsApi\AdWords\v201609\cm\AdGroup();
        $this->adGroups = NULL;
    }

    /**
     * Create an ad group based on given config.
     * @throws Exception
     */
    public function create() {

        if (!$this->config->getAdGroupName()) {
            throw new Exception("AdGroup name must be set in the config object in order to create ad group.");
        }

        if (!$this->config->getBid()) {
            throw new Exception("Bid amount must be set in the config object in order to create ad group.");
        }

        if (!$this->config->getCampaignId()) {
            throw new Exception("Campaign ID must be set in the config object in order to create ad group.");
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
            $this->adGroups = $this->downloadFromGoogle($this->config, $this->adGroupService);
        }

        return $this->adGroups;
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
     * Get the config object.
     * @return AdGroupConfig
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Set the config object.
     * @param AdGroupConfig $config
     * @return AdGroup
     */
    public function setConfig($config) {
        $this->config = $config;
        return $this;
    }

    /**
     * Get the list of ad groups.
     * @return null
     */
    public function getAdGroups() {
        return $this->adGroups;
    }

    /**
     * Get the list of ad groups.
     * @param null $adGroups
     * @return AdGroup
     */
    public function setAdGroups($adGroups) {
        $this->adGroups = $adGroups;
        return $this;
    }

    /**
     * Get the AdGroupService object.
     * @return \Google\AdsApi\Common\AdsSoapClient|\Google\AdsApi\Common\SoapClient
     */
    public function getAdGroupService() {
        return $this->adGroupService;
    }

    /**
     * Set the AdGroupService object.
     * @param \Google\AdsApi\Common\AdsSoapClient|\Google\AdsApi\Common\SoapClient $adGroupService
     * @return AdGroup
     */
    public function setAdGroupService($adGroupService) {
        $this->adGroupService = $adGroupService;
        return $this;
    }

}