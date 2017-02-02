<?php

namespace EasyAdwords\AdGroups;

use EasyAdwords\Config;
use Exception;
use Google\AdsApi\AdWords\v201609\cm\AdGroupStatus;


/**
 * Config class for AdGroup class.
 *
 * Class AdGroupConfig
 * @package EasyAdwords\AdGroups
 */
class AdGroupConfig extends Config {

    /**
     * @var string          The campaign ID of the ad group.
     */
    protected $campaignId;

    /**
     * @var string          The name of the ad group.
     */
    protected $adGroupName;

    /**
     * @var string          The ID of the ad group to operate on.
     */
    protected $adGroupId;

    /**
     * @var Google\AdsApi\AdWords\v201609\cm\AdGroupStatus  The status of the ad group, must be an AdGroupStatus instance.
     */
    protected $status;

    /**
     * @var integer         The bid amount to give the ad group, without extra zeros, e.g. 50 means 50$.
     */
    protected $bid;

    public function __construct(array $config) {
        parent::__construct($config);

        $campaignId = NULL;
        $adGroupName = NULL;
        $status = NULL;
        $bid = NULL;

        if (isset($config['campaignId'])) {
            $this->campaignId = $config['campaignId'];
        } else {
            throw new Exception("Campaign Id must be set in order to create an ad group object.");
        }

        if (isset($config['adGroupName'])) {
            $this->adGroupName = $config['adGroupName'];
        }

        if (isset($config['status'])) {
            $this->status = $config['status'];
        } else {
            $this->status = AdGroupStatus::PAUSED;
        }

        if (isset($config['bid'])) {
            $this->bid = $config['bid'];
        }
    }

    /**
     * @return mixed
     */
    public function getCampaignId() {
        return $this->campaignId;
    }

    /**
     * @param mixed $campaignId
     * @return AdGroupConfig
     */
    public function setCampaignId($campaignId) {
        $this->campaignId = $campaignId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdGroupName() {
        return $this->adGroupName;
    }

    /**
     * @param mixed $adGroupName
     * @return AdGroupConfig
     */
    public function setAdGroupName($adGroupName) {
        $this->adGroupName = $adGroupName;
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
     * @return AdGroupConfig
     */
    public function setStatus($status) {
        $this->status = $status;
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
     * @return AdGroupConfig
     */
    public function setBid($bid) {
        $this->bid = $bid;
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
     * @return AdGroupConfig
     */
    public function setAdGroupId($adGroupId) {
        $this->adGroupId = $adGroupId;
        return $this;
    }

}