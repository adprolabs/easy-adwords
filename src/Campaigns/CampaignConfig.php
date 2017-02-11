<?php

namespace EasyAdWords\Campaigns;

use EasyAdWords\Config;
use Google\AdsApi\AdWords\v201609\cm\AdServingOptimizationStatus;
use Google\AdsApi\AdWords\v201609\cm\AdvertisingChannelType;
use Google\AdsApi\AdWords\v201609\cm\BiddingStrategyType;
use Google\AdsApi\AdWords\v201609\cm\BudgetBudgetDeliveryMethod;
use Google\AdsApi\AdWords\v201609\cm\CampaignStatus;
use Google\AdsApi\AdWords\v201609\cm\Predicate;
use Google\AdsApi\AdWords\v201609\cm\PredicateOperator;
use Google\AdsApi\AdWords\v201609\cm\ServingStatus;

/**
 * Config class for Campaign class.
 *
 * Class CampaignConfig
 * @package EasyAdWords\Campaigns
 */
class CampaignConfig extends Config {

    /**
     * @var string                          ID of the campaign.
     */
    protected $campaignId;

    /**
     * @var string                          Name of the campaign.
     */
    protected $campaignName;

    /**
     * @var AdvertisingChannelType          Advertising channel of the campaign. Default is 'SEARCH'.
     */
    protected $advertisingChannelType;

    /**
     * @var CampaignStatus                  Status of the campaign. Default is 'PAUSED'.
     */
    protected $status;

    /**
     * @var integer                         Budget of the campaign, e.g. 50 means 50$. Default is 50.
     */
    protected $budget;

    /**
     * @var string                          Name of the budget.
     */
    protected $budgetName;

    /**
     * @var BiddingStrategyType             Bidding strategy type of the campaign. Default is 'MANUAL_CPC'.
     */
    protected $biddingStrategyType;

    /**
     * @var BudgetBudgetDeliveryMethod      Budget delivery method of the campaign. Default is 'STANDARD'.
     */
    protected $budgetDeliveryMethod;

    /**
     * @var boolean                         Target Google search if true. Default is true.
     */
    protected $targetGoogleSearch;

    /**
     * @var boolean                         Target search network if true. Default is true.
     */
    protected $targetSearchNetwork;

    /**
     * @var boolean                         Target content network if true. Default is true.
     */
    protected $targetContentNetwork;

    /**
     * @var false|string                    Start date of the campaign. Default is today.
     */
    protected $startDate;

    /**
     * @var false|string                    End date of the campaign.
     */
    protected $endDate;

    /**
     * @var AdServingOptimizationStatus     Ad serving optimization status of the campaign.
     */
    protected $adServingOptimizationStatus;

    /**
     * @var ServingStatus                   Serving status of the campaign. Default is 'SERVING'.
     */
    protected $servingStatus;

    /**
     * CampaignConfig constructor.
     * @param array $config
     */
    public function __construct(array $config) {
        parent::__construct($config);

        // Set the parameters to null.
        $this->campaignName = NULL;
        $this->advertisingChannelType = NULL;
        $this->status = NULL;
        $this->budget = NULL;
        $this->budgetName = NULL;
        $this->biddingStrategyType = NULL;
        $this->budgetDeliveryMethod = NULL;
        $this->targetGoogleSearch = NULL;
        $this->targetSearchNetwork = NULL;
        $this->targetContentNetwork = NULL;
        $this->startDate = NULL;
        $this->endDate = NULL;
        $this->adServingOptimizationStatus = NULL;
        $this->servingStatus = NULL;
        $this->campaignId = NULL;

        // If the 'useDefaults' option is given, set the defaults.
        if(isset($config['useDefaults']) && $config['useDefaults'] === true) {

            // Predefined defaults.
            $this->campaignName = NULL;
            $this->advertisingChannelType = AdvertisingChannelType::SEARCH;
            $this->status = CampaignStatus::PAUSED;
            $this->budget = 50;
            $this->budgetName = "EasyAdWords Budget #" . uniqid();
            $this->biddingStrategyType = BiddingStrategyType::MANUAL_CPC;
            $this->budgetDeliveryMethod = BudgetBudgetDeliveryMethod::STANDARD;
            $this->targetGoogleSearch = true;
            $this->targetSearchNetwork = true;
            $this->targetContentNetwork = true;
            $this->startDate = date('Ymd');
            $this->endDate = NULL;
            $this->adServingOptimizationStatus = NULL;
            $this->servingStatus = ServingStatus::SERVING;
            $this->campaignId = NULL;
        }

        if (isset($config['campaignName'])) {
            $this->campaignName = $config['campaignName'];
        }

        if (isset($config['campaignId'])) {
            $this->campaignId = $config['campaignId'];
            $this->addPredicate(new Predicate('Id', PredicateOperator::EQUALS, [$this->campaignId]));
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
     * Get the advertising channel type.
     * @return mixed
     */
    public function getAdvertisingChannelType() {
        return $this->advertisingChannelType;
    }

    /**
     * Set the advertising channel type.
     * @param mixed $advertisingChannelType
     * @return CampaignConfig
     */
    public function setAdvertisingChannelType($advertisingChannelType) {
        $this->advertisingChannelType = $advertisingChannelType;
        return $this;
    }

    /**
     * Get the status of the campaign.
     * @return mixed
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set the status of the campaign.
     * @param mixed $status
     * @return CampaignConfig
     */
    public function setStatus($status) {
        $this->status = $status;
        return $this;
    }

    /**
     * Get the budget of the campaign.
     * @return mixed
     */
    public function getBudget() {
        return $this->budget;
    }

    /**
     * Set the budget of the campaign.
     * @param mixed $budget
     * @return CampaignConfig
     */
    public function setBudget($budget) {
        $this->budget = $budget;
        return $this;
    }

    /**
     * Get the budget name.
     * @return string
     */
    public function getBudgetName() {
        return $this->budgetName;
    }

    /**
     * Set the budget name.
     * @param string $budgetName
     * @return CampaignConfig
     */
    public function setBudgetName($budgetName) {
        $this->budgetName = $budgetName;
        return $this;
    }

    /**
     * Get the bidding strategy type.
     * @return mixed
     */
    public function getBiddingStrategyType() {
        return $this->biddingStrategyType;
    }

    /**
     * Set the bidding strategy type.
     * @param mixed $biddingStrategyType
     * @return CampaignConfig
     */
    public function setBiddingStrategyType($biddingStrategyType) {
        $this->biddingStrategyType = $biddingStrategyType;
        return $this;
    }

    /**
     * Get the budget delivery method.
     * @return mixed
     */
    public function getBudgetDeliveryMethod() {
        return $this->budgetDeliveryMethod;
    }

    /**
     * Set the budget delivery method.
     * @param mixed $budgetDeliveryMethod
     * @return CampaignConfig
     */
    public function setBudgetDeliveryMethod($budgetDeliveryMethod) {
        $this->budgetDeliveryMethod = $budgetDeliveryMethod;
        return $this;
    }

    /**
     * Get the target google search value.
     * @return mixed
     */
    public function getTargetGoogleSearch() {
        return $this->targetGoogleSearch;
    }

    /**
     * Set the target google search value.
     * @param mixed $targetGoogleSearch
     * @return CampaignConfig
     */
    public function setTargetGoogleSearch($targetGoogleSearch) {
        $this->targetGoogleSearch = $targetGoogleSearch;
        return $this;
    }

    /**
     * Get the target search network value.
     * @return mixed
     */
    public function getTargetSearchNetwork() {
        return $this->targetSearchNetwork;
    }

    /**
     * Set the target search network value.
     * @param mixed $targetSearchNetwork
     * @return CampaignConfig
     */
    public function setTargetSearchNetwork($targetSearchNetwork) {
        $this->targetSearchNetwork = $targetSearchNetwork;
        return $this;
    }

    /**
     * Get the target content network value.
     * @return mixed
     */
    public function getTargetContentNetwork() {
        return $this->targetContentNetwork;
    }

    /**
     * Set the target content network value.
     * @param mixed $targetContentNetwork
     * @return CampaignConfig
     */
    public function setTargetContentNetwork($targetContentNetwork) {
        $this->targetContentNetwork = $targetContentNetwork;
        return $this;
    }

    /**
     * Get the start date of the campaign.
     * @return false|string
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * Set the start date of the campaign.
     * @param false|string $startDate
     * @return CampaignConfig
     */
    public function setStartDate($startDate) {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * Get the end date of the campaign.
     * @return false|string
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * Set the end date of the campaign.
     * @param false|string $endDate
     * @return CampaignConfig
     */
    public function setEndDate($endDate) {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Get the ad serving optimization status.
     * @return mixed
     */
    public function getAdServingOptimizationStatus() {
        return $this->adServingOptimizationStatus;
    }

    /**
     * Set the ad serving optimization status.
     * @param mixed $adServingOptimizationStatus
     * @return CampaignConfig
     */
    public function setAdServingOptimizationStatus($adServingOptimizationStatus) {
        $this->adServingOptimizationStatus = $adServingOptimizationStatus;
        return $this;
    }

    /**
     * Get the name of the campaign.
     * @return mixed
     */
    public function getCampaignName() {
        return $this->campaignName;
    }

    /**
     * Set the name of the campaign.
     * @param mixed $campaignName
     * @return CampaignConfig
     */
    public function setCampaignName($campaignName) {
        $this->campaignName = $campaignName;
        return $this;
    }

    /**
     * Get the serving status of the campaign.
     * @return string
     */
    public function getServingStatus() {
        return $this->servingStatus;
    }

    /**
     * Set the serving status of the campaign.
     * @param $servingStatus
     * @return $this
     */
    public function setServingStatus($servingStatus) {
        $this->servingStatus = $servingStatus;
        return $this;
    }

    /**
     * Get the ID of the campaign.
     * @return mixed
     */
    public function getCampaignId() {
        return $this->campaignId;
    }

    /**
     * Set the ID of the campaign.
     * @param mixed $campaignId
     * @return CampaignConfig
     */
    public function setCampaignId($campaignId) {
        $this->campaignId = $campaignId;
        return $this;
    }

}