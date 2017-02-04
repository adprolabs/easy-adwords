<?php

namespace EasyAdWords\Keywords;

use EasyAdWords\Config;

/**
 * Config class for KeywordBatch class.
 *
 * Class KeywordBatchConfig
 * @package EasyAdWords\Keywords
 */
class KeywordBatchConfig extends Config {

    /**
     * @var integer     The batch size of the mutate operations. The default is 2000.
     */
    protected $batchSize;

    /**
     * KeywordBatchConfig constructor.
     * @param array $config
     */
    public function __construct(array $config) {
        parent::__construct($config);

        $this->batchSize = 2000;

        if (isset($config['batchSize'])) {
            $this->batchSize = $config['batchSize'];
        }
    }

    /**
     * Get the batch size.
     * @return mixed
     */
    public function getBatchSize() {
        return $this->batchSize;
    }

    /**
     * Set the batchSize.
     * @param mixed $batchSize
     * @return KeywordBatchConfig
     */
    public function setBatchSize($batchSize) {
        $this->batchSize = $batchSize;
        return $this;
    }
}