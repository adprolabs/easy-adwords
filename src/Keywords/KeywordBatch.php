<?php

namespace EasyAdwords\Keywords;

class KeywordBatch extends KeywordBase  {
    protected $keywords;
    protected $keywordOperations;
    protected $batchSize;
    protected $operationResult;

    public function __construct($batchSize = 2000) {
        parent::__construct();

        $this->batchSize = $batchSize;
        $this->keywords = array();
        $this->keywordOperations = array();
        $this->operationResult = array();
    }

    public function append(KeywordConfig $config){
        $this->keywords[] = $config;
        $this->keywordOperations[] = $this->createKeywordOperation($config);
    }

    public function mutate(){
        $batchOperations = array_chunk($this->keywordOperations, $this->batchSize);
        foreach ($batchOperations as $operations) {
            $this->operationResult[] = $this->adGroupCriterionService->mutate($operations);
        }
    }
}