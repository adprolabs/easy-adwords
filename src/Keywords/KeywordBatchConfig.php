<?php

namespace EasyAdwords\Keywords;

use EasyAdwords\Config;

/**
 * Config class for KeywordBatch class.
 *
 * Class KeywordBatchConfig
 * @package EasyAdwords\Keywords
 */
class KeywordBatchConfig extends Config {

    protected $batchSize;

    public function __construct(array $config) {
        parent::__construct($config);

        $this->batchSize = 2000;

        if (isset($config['batchSize'])) {
            $this->batchSize = $config['batchSize'];
        }
    }

    /**
     * @return mixed
     */
    public function getBatchSize() {
        return $this->batchSize;
    }

    /**
     * @param mixed $batchSize
     * @return KeywordBatchConfig
     */
    public function setBatchSize($batchSize) {
        $this->batchSize = $batchSize;
        return $this;
    }
}