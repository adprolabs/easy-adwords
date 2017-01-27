<?php

use EasyAdwords\Config;
use Google\AdsApi\AdWords\v201609\cm\AdvertisingChannelType;
use Google\AdsApi\AdWords\v201609\cm\BiddingStrategyType;
use Google\AdsApi\AdWords\v201609\cm\BudgetBudgetDeliveryMethod;
use Google\AdsApi\AdWords\v201609\cm\CampaignStatus;

class CampaignConfig extends Config {

    protected $campaignName;
    protected $advertisingChannelType;
    protected $status;
    protected $budget;
    protected $budgetName;
    protected $biddingStrategyType;
    protected $budgetDeliveryMethod;
    protected $targetGoogleSearch;
    protected $targetSearchNetwork;
    protected $targetContentNetwork;
    protected $startDate;
    protected $endDate;
    protected $adServingOptimizationStatus;

    public function __construct(array $config) {
        parent::__construct($config);

        $this->campaignName = NULL;
        $this->advertisingChannelType = AdvertisingChannelType::SEARCH;
        $this->status = CampaignStatus::PAUSED;
        $this->budget = 50000000;
        $this->budgetName = "EasyAdwords Budget #" . uniqid();
        $this->biddingStrategyType = BiddingStrategyType::MANUAL_CPC;
        $this->budgetDeliveryMethod = BudgetBudgetDeliveryMethod::STANDARD;
        $this->targetGoogleSearch = true;
        $this->targetSearchNetwork = true;
        $this->targetContentNetwork = true;
        $this->startDate = date('Ymd');
        $this->endDate = NULL;
        $this->adServingOptimizationStatus = NULL;

        if (isset($config['campaignName'])) {
            $this->campaignName = $config['campaignName'];
        } else {
            throw new Exception("Campaign name must be set to create campaign.");
        }

        if (isset($config['advertisingChannelType'])) {
            $this->advertisingChannelType = $config['advertisingChannelType'];
        }

        if (isset($config['status'])) {
            $this->status = $config['status'];
        }

        if (isset($config['budget'])) {
            $this->budget = $config['budget'];
        }

        if (isset($config['biddingStrategyType'])) {
            $this->biddingStrategyType = $config['biddingStrategyType'];
        }

        if (isset($config['budgetDeliveryMethod'])) {
            $this->budgetDeliveryMethod = $config['budgetDeliveryMethod'];
        }

        if (isset($config['targetGoogleSearch'])) {
            $this->targetGoogleSearch = $config['targetGoogleSearch'];
        }

        if (isset($config['targetSearchNetwork'])) {
            $this->targetSearchNetwork = $config['targetSearchNetwork'];
        }

        if (isset($config['targetContentNetwork'])) {
            $this->targetContentNetwork = $config['targetContentNetwork'];
        }

        if (isset($config['startDate'])) {
            $this->startDate = date('Ymd', strtotime($config['startDate']));
        }

        if (isset($config['endDate'])) {
            $this->endDate = date('Ymd', strtotime($config['endDate']));
        }

        if (isset($config['adServingOptimizationStatus'])) {
            $this->adServingOptimizationStatus = $config['adServingOptimizationStatus'];
        }
    }


    /**
     * @return mixed
     */
    public function getAdvertisingChannelType() {
        return $this->advertisingChannelType;
    }

    /**
     * @param mixed $advertisingChannelType
     * @return CampaignConfig
     */
    public function setAdvertisingChannelType($advertisingChannelType) {
        $this->advertisingChannelType = $advertisingChannelType;
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
     * @return CampaignConfig
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBudget() {
        return $this->budget;
    }

    /**
     * @param mixed $budget
     * @return CampaignConfig
     */
    public function setBudget($budget) {
        $this->budget = $budget;
        return $this;
    }

    /**
     * @return string
     */
    public function getBudgetName() {
        return $this->budgetName;
    }

    /**
     * @param string $budgetName
     * @return CampaignConfig
     */
    public function setBudgetName($budgetName) {
        $this->budgetName = $budgetName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBiddingStrategyType() {
        return $this->biddingStrategyType;
    }

    /**
     * @param mixed $biddingStrategyType
     * @return CampaignConfig
     */
    public function setBiddingStrategyType($biddingStrategyType) {
        $this->biddingStrategyType = $biddingStrategyType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBudgetDeliveryMethod() {
        return $this->budgetDeliveryMethod;
    }

    /**
     * @param mixed $budgetDeliveryMethod
     * @return CampaignConfig
     */
    public function setBudgetDeliveryMethod($budgetDeliveryMethod) {
        $this->budgetDeliveryMethod = $budgetDeliveryMethod;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTargetGoogleSearch() {
        return $this->targetGoogleSearch;
    }

    /**
     * @param mixed $targetGoogleSearch
     * @return CampaignConfig
     */
    public function setTargetGoogleSearch($targetGoogleSearch) {
        $this->targetGoogleSearch = $targetGoogleSearch;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTargetSearchNetwork() {
        return $this->targetSearchNetwork;
    }

    /**
     * @param mixed $targetSearchNetwork
     * @return CampaignConfig
     */
    public function setTargetSearchNetwork($targetSearchNetwork) {
        $this->targetSearchNetwork = $targetSearchNetwork;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTargetContentNetwork() {
        return $this->targetContentNetwork;
    }

    /**
     * @param mixed $targetContentNetwork
     * @return CampaignConfig
     */
    public function setTargetContentNetwork($targetContentNetwork) {
        $this->targetContentNetwork = $targetContentNetwork;
        return $this;
    }

    /**
     * @return false|string
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * @param false|string $startDate
     * @return CampaignConfig
     */
    public function setStartDate($startDate) {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return false|string
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * @param false|string $endDate
     * @return CampaignConfig
     */
    public function setEndDate($endDate) {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAdServingOptimizationStatus() {
        return $this->adServingOptimizationStatus;
    }

    /**
     * @param mixed $adServingOptimizationStatus
     * @return CampaignConfig
     */
    public function setAdServingOptimizationStatus($adServingOptimizationStatus) {
        $this->adServingOptimizationStatus = $adServingOptimizationStatus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCampaignName() {
        return $this->campaignName;
    }

    /**
     * @param mixed $campaignName
     * @return CampaignConfig
     */
    public function setCampaignName($campaignName) {
        $this->campaignName = $campaignName;
        return $this;
    }
}