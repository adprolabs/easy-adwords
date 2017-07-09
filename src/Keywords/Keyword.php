<?php

namespace EasyAdWords\Keywords;

use EasyAdWords\EntityInterface;
use Exception;
use Google\AdsApi\AdWords\v201705\cm\AdGroupCriterion;
use Google\AdsApi\AdWords\v201705\cm\AdGroupCriterionOperation;
use Google\AdsApi\AdWords\v201705\cm\Criterion;
use Google\AdsApi\AdWords\v201705\cm\Operator;


/**
 * Base class for basic keyword operations.
 * Operates based on the given KeywordConfig object.
 *
 * Class Keyword
 * @package EasyAdWords\Keywords
 */
class Keyword extends KeywordBase implements EntityInterface {

    /**
     * @var KeywordConfig                   Keyword config object to operate on.
     */
    protected $config;

    /**
     * @var null|array                      The list of keywords, result of the get operation.
     */
    protected $keywords;

    /**
     * Keyword constructor.
     * @param KeywordConfig $config
     */
    public function __construct(KeywordConfig $config) {
        parent::__construct($config);

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

        // Create a criterion object.
        $criterion = new Criterion();

        // Check if the keyword ID is set.
        if ($this->config->getKeywordId()) {
            $criterion->setId($this->config->getKeywordId());
        } else {
            throw new Exception("Keyword ID (Criterion ID) must be set in the config object in order to remove a keyword.");
        }

        // Create an ad group criterion.
        $adGroupCriterion = new AdGroupCriterion();

        // Check if the ad group ID is set.
        if ($this->config->getAdGroupId()) {
            $adGroupCriterion->setAdGroupId($this->config->getAdGroupId());
        } else {
            throw new Exception("Ad group ID must be set in the config object in order to remove a keyword.");
        }

        // Set the criterion on ad group object.
        $adGroupCriterion->setCriterion($criterion);

        // Create an ad group criterion operation.
        $operation = new AdGroupCriterionOperation();
        $operation->setOperand($adGroupCriterion);
        $operation->setOperator(Operator::REMOVE);

        // Remove the criterion on the server.
        $this->operationResult = $this->adGroupCriterionService->mutate([$operation]);
    }

    /**
     * Add a single keyword based on given config.
     */
    private function addSingleKeyword() {

        // Create the keyword operation.
        $operation = $this->createKeywordOperation($this->config);

        // Mutate the operation.
        $this->operationResult = $this->adGroupCriterionService->mutate([$operation]);
    }

    /**
     * Get config object.
     * @return KeywordConfig
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Set config object.
     * @param KeywordConfig $config
     * @return Keyword
     */
    public function setConfig($config) {
        $this->config = $config;
        return $this;
    }

    /**
     * Get keywords.
     * @return null
     */
    public function getKeywords() {
        return $this->keywords;
    }

    /**
     * Set keywords.
     * @param null $keywords
     * @return Keyword
     */
    public function setKeywords($keywords) {
        $this->keywords = $keywords;
        return $this;
    }

}