<?php

namespace EasyAdwords\Keywords;

/**
 * Base class for batch keyword generation operations.
 * Operates based on the given list of KeywordConfig objects.
 * Class KeywordBatch
 * @package EasyAdwords\Keywords
 */
class KeywordBatch extends KeywordBase {

    /**
     * @var KeywordBatchConfig          Batch operation config file.
     */
    protected $config;

    /**
     * @var array                       Array of keywords to mutate.
     */
    protected $keywords;

    /**
     * @var array                       Array of keyword operations to mutate.
     */
    protected $keywordOperations;

    /**
     * @var array                       Result of the mutate operations for each batch.
     */
    protected $operationResult;

    /**
     * KeywordBatch constructor.
     * @param KeywordBatchConfig $config
     */
    public function __construct(KeywordBatchConfig $config) {
        parent::__construct($config);

        $this->config = $config;
        $this->keywords = array();
        $this->keywordOperations = array();
        $this->operationResult = array();
    }

    /**
     * Append the given config file to
     * @param KeywordConfig $config
     */
    public function append(KeywordConfig $config) {
        $this->keywords[] = $config;
        $this->keywordOperations[] = $this->createKeywordOperation($config);
    }

    /**
     * Mutate the operations as chunks based on the given batch size.
     * Results of the operations are pushed into the operationResult array.
     */
    public function mutate() {
        $batchOperations = array_chunk($this->keywordOperations, $this->config->getBatchSize());
        foreach ($batchOperations as $operations) {
            $this->operationResult[] = $this->adGroupCriterionService->mutate($operations);
        }
    }

    /**
     * Get config object.
     * @return KeywordBatchConfig
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Set config object.
     * @param KeywordBatchConfig $config
     * @return KeywordBatch
     */
    public function setConfig($config) {
        $this->config = $config;
        return $this;
    }

    /**
     * Get keywords.
     * @return array
     */
    public function getKeywords() {
        return $this->keywords;
    }

    /**
     * Set keywords.
     * @param array $keywords
     * @return KeywordBatch
     */
    public function setKeywords($keywords) {
        $this->keywords = $keywords;
        return $this;
    }

    /**
     * Get array of keyword operation objects.
     * @return array
     */
    public function getKeywordOperations() {
        return $this->keywordOperations;
    }

    /**
     * Set array of keyword operation objects.
     * @param array $keywordOperations
     * @return KeywordBatch
     */
    public function setKeywordOperations($keywordOperations) {
        $this->keywordOperations = $keywordOperations;
        return $this;
    }

    /**
     * Get result of the batch operation.
     * @return array
     */
    public function getOperationResult() {
        return $this->operationResult;
    }

    /**
     * Set result of the batch operation.
     * @param array $operationResult
     * @return KeywordBatch
     */
    public function setOperationResult($operationResult) {
        $this->operationResult = $operationResult;
        return $this;
    }
}