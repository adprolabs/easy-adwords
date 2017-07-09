<?php

namespace EasyAdWords\AdGroups;

use EasyAdWords\Config;
use Exception;
use Google\AdsApi\AdWords\v201705\cm\AdGroupStatus;


/**
 * Config class for AdGroup class.
 *
 * Class AdGroupConfig
 * @package EasyAdWords\AdGroups
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
     * @var AdGroupStatus   The status of the ad group, must be an AdGroupStatus instance.
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
     * Get the campaign ID.
     * @return mixed
     */
    public function getCampaignId() {
        return $this->campaignId;
    }

    /**
     * Set the campaign ID.
     * @param mixed $campaignId
     * @return AdGroupConfig
     */
    public function setCampaignId($campaignId) {
        $this->campaignId = $campaignId;
        return $this;
    }

    /**
     * Get the ad group name.
     * @return mixed
     */
    public function getAdGroupName() {
        return $this->adGroupName;
    }

    /**
     * Set the ad group name.
     * @param mixed $adGroupName
     * @return AdGroupConfig
     */
    public function setAdGroupName($adGroupName) {
        $this->adGroupName = $adGroupName;
        return $this;
    }

    /**
     * Get the ad group status.
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set the ad group status.
     * @param mixed $status
     * @return AdGroupConfig
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the ad group bid.
     * @return mixed
     */
    public function getBid() {
        return $this->bid;
    }

    /**
     * Set the ad group bid.
     * @param mixed $bid
     * @return AdGroupConfig
     */
    public function setBid($bid) {
        $this->bid = $bid;
        return $this;
    }

    /**
     * Get the ad group ID.
     * @return mixed
     */
    public function getAdGroupId() {
        return $this->adGroupId;
    }

    /**
     * Set the ad group ID.
     * @param mixed $adGroupId
     * @return AdGroupConfig
     */
    public function setAdGroupId($adGroupId) {
        $this->adGroupId = $adGroupId;
        return $this;
    }

}