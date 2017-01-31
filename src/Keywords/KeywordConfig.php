<?php

namespace EasyAdwords\Keywords;

use EasyAdwords\Config;

class KeywordConfig extends Config {
    protected $keyword;
    protected $keywordId;
    protected $matchType;
    protected $finalUrls;
    protected $adGroupId;
    protected $bid;
    protected $status;

    public function __construct(array $config) {
        parent::__construct($config);

        $this->keyword = NULL;
        $this->matchType = NULL;
        $this->finalUrls = NULL;
        $this->adGroupId = NULL;
        $this->bid = NULL;
        $this->status = NULL;

        if (isset($config['keyword'])) {
            $this->keyword = $config['keyword'];
        }

        if (isset($config['keywordId'])) {
            $this->keywordId = $config['keywordId'];
        }

        if (isset($config['matchType'])) {
            $this->matchType = $config['matchType'];
        }

        if (isset($config['finalUrls'])) {
            if (!is_array($config['finalUrls'])) {
                $config['finalUrls'] = [$config['finalUrls']];
            }
            $this->finalUrls = $config['finalUrls'];
        }

        if (isset($config['adGroupId'])) {
            $this->adGroupId = $config['adGroupId'];
        }

        if (isset($config['bid'])) {
            $this->bid = $config['bid'];
        }

        if (isset($config['status'])) {
            $this->status = $config['status'];
        }
    }


    /**
     * @return mixed
     */
    public function getKeyword() {
        return $this->keyword;
    }

    /**
     * @param mixed $keyword
     * @return KeywordConfig
     */
    public function setKeyword($keyword) {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMatchType() {
        return $this->matchType;
    }

    /**
     * @param mixed $matchType
     * @return KeywordConfig
     */
    public function setMatchType($matchType) {
        $this->matchType = $matchType;
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function getFinalUrls() {
        return $this->finalUrls;
    }

    /**
     * @param array|mixed $finalUrls
     * @return KeywordConfig
     */
    public function setFinalUrls($finalUrls) {
        $this->finalUrls = $finalUrls;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdGroupId() {
        return $this->adGroupId;
    }

    /**
     * @param mixed $adGroupId
     * @return KeywordConfig
     */
    public function setAdGroupId($adGroupId) {
        $this->adGroupId = $adGroupId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBid() {
        return $this->bid;
    }

    /**
     * @param mixed $bid
     * @return KeywordConfig
     */
    public function setBid($bid) {
        $this->bid = $bid;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param mixed $status
     * @return KeywordConfig
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getKeywordId() {
        return $this->keywordId;
    }

    /**
     * @param $keywordId
     * @return $this
     */
    public function setKeywordId($keywordId) {
        $this->keywordId = $keywordId;
        return $this;
    }
}