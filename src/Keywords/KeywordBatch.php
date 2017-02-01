<?php

namespace EasyAdwords\Keywords;

/**
 * Base class for batch keyword generation operations.
 * Operates based on the given list of KeywordConfig objects.
 *
 * Class KeywordBatch
 * @package EasyAdwords\Keywords
 */
class KeywordBatch extends KeywordBase {
    protected $config;
    protected $keywords;
    protected $keywordOperations;
    protected $operationResult;

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
}