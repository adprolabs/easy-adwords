<?php

namespace EasyAdWords\Keywords;

use EasyAdWords\Config;
use Google\AdsApi\AdWords\v201802\cm\KeywordMatchType;
use Google\AdsApi\AdWords\v201802\cm\UserStatus;

/**
 * Config class for Keyword class.
 *
 * Class KeywordConfig
 * @package EasyAdWords\Keywords
 */
class KeywordConfig extends Config {

    /**
     * @var array               Name of the keyword.
     */
    protected $keyword;

    /**
     * @var string              ID of the keyword to operate on.
     */
    protected $keywordId;

    /**
     * @var KeywordMatchType    Match type of the keyword.
     */
    protected $matchType;

    /**
     * @var array               Array of the final URLs for the keyword.
     */
    protected $finalUrls;

    /**
     * @var string              ID of the ad group of the keyword.
     */
    protected $adGroupId;

    /**
     * @var integer             Bid of the keyword.
     */
    protected $bid;

    /**
     * @var UserStatus          Status of the keyword.
     */
    protected $status;

    /**
     * KeywordConfig constructor.
     * @param array $config
     */
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
     * Get keyword.
     * @return array
     */
    public function getKeyword() {
        return $this->keyword;
    }

    /**
     * Set keyword.
     * @param array $keyword
     * @return KeywordConfig
     */
    public function setKeyword($keyword) {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * Get keyword ID.
     * @return string
     */
    public function getKeywordId() {
        return $this->keywordId;
    }

    /**
     * Set keyword ID.
     * @param string $keywordId
     * @return KeywordConfig
     */
    public function setKeywordId($keywordId) {
        $this->keywordId = $keywordId;
        return $this;
    }

    /**
     * Get keyword match type.
     * @return KeywordMatchType
     */
    public function getMatchType() {
        return $this->matchType;
    }

    /**
     * Set keyword match type.
     * @param KeywordMatchType $matchType
     * @return KeywordConfig
     */
    public function setMatchType($matchType) {
        $this->matchType = $matchType;
        return $this;
    }

    /**
     * Get final URLs.
     * @return array
     */
    public function getFinalUrls() {
        return $this->finalUrls;
    }

    /**
     * Set final URLs.
     * @param array $finalUrls
     * @return KeywordConfig
     */
    public function setFinalUrls($finalUrls) {
        $this->finalUrls = $finalUrls;
        return $this;
    }

    /**
     * Get ad group ID.
     * @return string
     */
    public function getAdGroupId() {
        return $this->adGroupId;
    }

    /**
     * Set ad group ID.
     * @param string $adGroupId
     * @return KeywordConfig
     */
    public function setAdGroupId($adGroupId) {
        $this->adGroupId = $adGroupId;
        return $this;
    }

    /**
     * Get bid of the keyword.
     * @return int
     */
    public function getBid() {
        return $this->bid;
    }

    /**
     * Set bid of the keyword.
     * @param int $bid
     * @return KeywordConfig
     */
    public function setBid($bid) {
        $this->bid = $bid;
        return $this;
    }

    /**
     * Get status of the keyword.
     * @return UserStatus
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set status of the keyword.
     * @param UserStatus $status
     * @return KeywordConfig
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }
}