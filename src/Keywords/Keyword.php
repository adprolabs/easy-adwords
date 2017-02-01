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

class Keyword extends KeywordBase implements EntityInterface {

    protected $config;
    protected $keywords;
    protected $adGroupCriterionObject;

    public function __construct(KeywordConfig $config) {
        parent::__construct();

        $this->config = $config;
        $this->keywords = NULL;
    }

    /**
     * Create a keyword based on given config.
     * @throws Exception
     */
    public function create() {

        if (!is_array($this->config->getKeyword())) {
            $this->addSingleKeyword();
        } else {
            throw new Exception("The keyword must be a single keyword, not an array. If you want to perform multiple 
            keyword operations, use KeywordBatch object.");
        }
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


    private function addSingleKeyword() {

        // Create the keyword operation.
        $operation = $this->createKeywordOperation($this->config);

        // Mutate the operation.
        $this->operationResult = $this->adGroupCriterionService->mutate([$operation]);
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

}