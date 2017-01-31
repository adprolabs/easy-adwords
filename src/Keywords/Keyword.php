<?php

namespace EasyAdwords\Keywords;

use EasyAdwords\Entity;
use EasyAdwords\EntityInterface;
use Exception;
use Google\AdsApi\AdWords\v201609\cm\AdGroupCriterion;
use Google\AdsApi\AdWords\v201609\cm\AdGroupCriterionOperation;
use Google\AdsApi\AdWords\v201609\cm\AdGroupCriterionService;
use Google\AdsApi\AdWords\v201609\cm\AdGroupOperation;
use Google\AdsApi\AdWords\v201609\cm\BiddableAdGroupCriterion;
use Google\AdsApi\AdWords\v201609\cm\BiddingStrategyConfiguration;
use Google\AdsApi\AdWords\v201609\cm\CpcBid;
use Google\AdsApi\AdWords\v201609\cm\Criterion;
use Google\AdsApi\AdWords\v201609\cm\KeywordMatchType;
use Google\AdsApi\AdWords\v201609\cm\Money;
use Google\AdsApi\AdWords\v201609\cm\Operator;
use Google\AdsApi\AdWords\v201609\cm\Selector;

class Keyword extends Entity implements EntityInterface {

    protected $config;
    protected $keywords;
    protected $adGroupCriterionService;
    protected $adGroupCriterionObject;

    public function __construct(KeywordConfig $config) {
        parent::__construct();
        $this->config = $config;

        // Build the campaign service.
        $this->adGroupCriterionService = $this->adWordsServices->get($this->authObject->getSession(), AdGroupCriterionService::class);
        $this->adGroupCriterionObject = new \Google\AdsApi\AdWords\v201609\cm\Keyword();
        $this->keywords = NULL;
    }

    /**
     * Create a keyword based on given config.
     * @throws Exception
     */
    public function create() {

        // Set the text and the match types of the criterion object.
        $this->adGroupCriterionObject->setText($this->config->getKeyword());
        $this->adGroupCriterionObject->setMatchType($this->config->getMatchType());

        // Create a new biddable ad group criterion.
        $adGroupCriterion = new BiddableAdGroupCriterion();
        if ($this->config->getAdGroupId()) {
            $adGroupCriterion->setAdGroupId($this->config->getAdGroupId());
        } else {
            throw new Exception("Ad group ID must be set in the config object in order to create a keyword.");
        }

        $adGroupCriterion->setCriterion($this->adGroupCriterionObject);

        // Set status if given in the config.
        if ($this->config->getStatus()) {
            $adGroupCriterion->setUserStatus($this->config->getStatus());
        }

        // Set final urls if given in the config.
        if ($this->config->getFinalUrls()) {
            $adGroupCriterion->setFinalUrls($this->config->getFinalUrls());
        }

        // Set bids if given in the config.
        if ($this->config->getBid()) {
            $adGroupCriterion->setBiddingStrategyConfiguration($this->setBiddingConfiguration());
        }

        $operation = new AdGroupCriterionOperation();
        $operation->setOperand($adGroupCriterion);
        $operation->setOperator(Operator::ADD);


        // Mutate the operation.
        $this->operationResult = $this->adGroupCriterionService->mutate([$operation]);
    }

    /**
     * List all the keywords with the given fields and predicates.
     * Works as an alias of "getKeywords" if the list is already downloaded before.
     * @return null
     */
    public function get() {

        if (!$this->keywords) {
            $this->downloadFromGoogle($this->config, $this->adGroupCriterionService);
        }

        return $this->keywords;
    }

    /**
     * Remove a keyword given its keyword ID and ad group ID.
     * @throws Exception
     */
    public function remove() {

        $criterion = new Criterion();
        if ($this->config->getKeywordId()) {
            $criterion->setId($this->config->getKeywordId());
        } else {
            throw new Exception("Keyword ID (Criterion ID) must be set in the config object in order to remove a keyword.");
        }

        // Create an ad group criterion.
        $adGroupCriterion = new AdGroupCriterion();
        if ($this->config->getAdGroupId()) {
            $adGroupCriterion->setAdGroupId($this->config->getAdGroupId());
        } else {
            throw new Exception("Ad group ID must be set in the config object in order to remove a keyword.");
        }

        $adGroupCriterion->setCriterion($criterion);

        // Create an ad group criterion operation.
        $operation = new AdGroupCriterionOperation();
        $operation->setOperand($adGroupCriterion);
        $operation->setOperator(Operator::REMOVE);

        // Remove criterion on the server.
        $this->operationResult = $this->adGroupCriterionService->mutate([$operation]);
    }

    /**
     * Create a bidding strategy configuration object based on the config.
     * @return BiddingStrategyConfiguration
     */
    private function setBiddingConfiguration() {
        $bid = new CpcBid();
        $money = new Money();
        $money->setMicroAmount($this->config->getBid() * 1000000);
        $bid->setBid($money);
        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBids([$bid]);
        return $biddingStrategyConfiguration;
    }

    /**
     * @return KeywordConfig
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * @param KeywordConfig $config
     * @return Keyword
     */
    public function setConfig($config) {
        $this->config = $config;
        return $this;
    }

    /**
     * @return null
     */
    public function getKeywords() {
        return $this->keywords;
    }

    /**
     * @param null $keywords
     * @return Keyword
     */
    public function setKeywords($keywords) {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * @return \Google\AdsApi\Common\AdsSoapClient|\Google\AdsApi\Common\SoapClient
     */
    public function getAdGroupCriterionService() {
        return $this->adGroupCriterionService;
    }

    /**
     * @param \Google\AdsApi\Common\AdsSoapClient|\Google\AdsApi\Common\SoapClient $adGroupCriterionService
     * @return Keyword
     */
    public function setAdGroupCriterionService($adGroupCriterionService) {
        $this->adGroupCriterionService = $adGroupCriterionService;
        return $this;
    }

    /**
     * @return \Google\AdsApi\AdWords\v201609\cm\Keyword
     */
    public function getAdGroupCriterionObject() {
        return $this->adGroupCriterionObject;
    }

    /**
     * @param \Google\AdsApi\AdWords\v201609\cm\Keyword $adGroupCriterionObject
     * @return Keyword
     */
    public function setAdGroupCriterionObject($adGroupCriterionObject) {
        $this->adGroupCriterionObject = $adGroupCriterionObject;
        return $this;
    }
}