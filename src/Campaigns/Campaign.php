<?php

namespace EasyAdWords\Campaigns;

use EasyAdWords\AdWordsAuth\AdWordsAuth;
use EasyAdWords\Entity;
use EasyAdWords\EntityInterface;
use Exception;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\v201609\cm\BiddingStrategyConfiguration;
use Google\AdsApi\AdWords\v201609\cm\Budget;
use Google\AdsApi\AdWords\v201609\cm\BudgetOperation;
use Google\AdsApi\AdWords\v201609\cm\BudgetService;
use Google\AdsApi\AdWords\v201609\cm\CampaignOperation;
use Google\AdsApi\AdWords\v201609\cm\CampaignService;
use Google\AdsApi\AdWords\v201609\cm\CampaignStatus;
use Google\AdsApi\AdWords\v201609\cm\Money;
use Google\AdsApi\AdWords\v201609\cm\NetworkSetting;
use Google\AdsApi\AdWords\v201609\cm\Operator;

/**
 * Base class for basic campaign operations.
 * Operates based on the given CampaignConfig object.
 *
 * Class Campaign
 * @package EasyAdWords\Campaigns
 */
class Campaign extends Entity implements EntityInterface {

    /**
     * @var \Google\AdsApi\AdWords\v201609\cm\Campaign  The campaign object that will be used in operations.
     */
    protected $campaignObject;

    /**
     * @var CampaignConfig          Config object that is used accross the operations.
     */
    protected $config;

    /**
     * @var \Google\AdsApi\Common\AdsSoapClient|\Google\AdsApi\Common\SoapClient  Campaign service to connect.
     */
    protected $campaignService;

    /**
     * @var null|array                List of the downloaded campaigns.
     */
    protected $campaigns;

    /**
     * Campaign constructor.
     * @param CampaignConfig $config
     */
    public function __construct(CampaignConfig $config) {

        parent::__construct($config);

        $this->config = $config;

        // Build the campaign service.
        $this->campaignService = $this->adWordsServices->get($this->authObject->getSession(), CampaignService::class);

        // Build the singular campaign object.
        $this->campaignObject = new \Google\AdsApi\AdWords\v201609\cm\Campaign();
        $this->campaigns = NULL;
    }

    /**
     * Create a campaign based on given config information.
     * @throws Exception
     */
    public function create() {

        // Check if the given configuration is correct to create a campaign.
        $this->checkCreateConfig();

        // Create a campaign with given settings.
        $this->campaignObject->setName($this->config->getCampaignName());
        $this->campaignObject->setAdvertisingChannelType($this->config->getAdvertisingChannelType());

        // Set shared budget (required).
        $this->createBudget();

        // Set the bidding strategy.
        $this->setBiddingStrategyObject();

        // Set network targeting.
        $this->setNetworkTargetingObject();

        // Set additional settings (optional).
        $this->campaignObject->setStatus($this->config->getStatus());
        $this->campaignObject->setStartDate($this->config->getStartDate());
        $this->campaignObject->setEndDate($this->config->getEndDate());
        $this->campaignObject->setAdServingOptimizationStatus($this->config->getAdServingOptimizationStatus());
        $this->campaignObject->setServingStatus($this->config->getServingStatus());

        // Create a campaign operation and add it to the operations list.
        $operation = new CampaignOperation();
        $operation->setOperand($this->campaignObject);
        $operation->setOperator(Operator::ADD);

        // Create the campaigns on the server.
        $result = $this->campaignService->mutate([$operation]);
        $this->operationResult = $result->getValue();
        return $this;
    }

    /**
     * List all the campaigns with the given fields and predicates.
     * Works as an alias of "getCampaigns" if the list is already downloaded before.
     * @param bool $isPaginated
     * @return array|mixed|null
     */
    public function get() {

        // If the campaigns are not already downloaded, download them.
        if (!$this->campaigns) {
            $this->campaigns = $this->downloadFromGoogle($this->config, $this->campaignService);
        }

        return $this->campaigns;
    }

    /**
     * Remove a campaign given its campaign ID.
     * @throws Exception
     */
    public function remove() {

        if (!$this->config->getCampaignId()) {
            throw new Exception("Campaign ID must be set in the config object in order to remove a campaign.");
        }

        // Identify the campaign.
        $this->campaignObject->setId($this->config->getCampaignId());
        $this->campaignObject->setStatus(CampaignStatus::REMOVED);

        // Create a campaign operation and add it to the list.
        $operation = new CampaignOperation();
        $operation->setOperand($this->campaignObject);
        $operation->setOperator(Operator::SET);

        // Remove the campaign on the server.
        $result = $this->campaignService->mutate([$operation]);
        $this->operationResult = $result->getValue()[0];
    }

    private function checkCreateConfig() {
        if (!$this->config->getCampaignName()) {
            throw new Exception("Campaign name must be set in the config object in order to create campaign.");
        }
        if($this->config->getAdvertisingChannelType()) {
            throw new Exception('Advertising channel type must be set in order to create a campaign.');
        }
        if($this->config->getStatus()) {
            throw new Exception('Campaign status must be set in order to create a campaign.');
        }
        if($this->config->getBudget()) {
            throw new Exception('Budget must be set in order to create a campaign.');
        }
        if($this->config->getBiddingStrategyType()) {
            throw new Exception('Bidding strategy must be set in order to create a campaign.');
        }
        if($this->config->getBudgetDeliveryMethod()) {
            throw new Exception('Budget delivery method must be set in order to create a campaign.');
        }
        if($this->config->getTargetGoogleSearch()) {
            throw new Exception('Target Google Search must be set as boolean in order to create a campaign.');
        }
        if($this->config->getTargetSearchNetwork()) {
            throw new Exception('Target Search Network must be set as boolean in order to create a campaign.');
        }
        if($this->config->getTargetContentNetwork()) {
            throw new Exception('Target Content Network must be set as boolean in order to create a campaign.');
        }
        if($this->config->getStartDate()) {
            throw new Exception('Start Date must be set in order to create a campaign.');
        }
        if($this->config->getServingStatus()) {
            throw new Exception('Serving status must be set in order to create a campaign.');
        }
    }

    /**
     * Create the budget object and apply the budget to the campaign object.
     */
    private function createBudget() {

        // Create the budget service.
        $budgetService = $this->adWordsServices->get($this->authObject->getSession(), BudgetService::class);

        // Create the shared budget.
        $money = new Money();
        $money->setMicroAmount($this->config->getBudget() * 1000000);
        $budget = new Budget();
        $budget->setName($this->config->getBudgetName());
        $budget->setAmount($money);
        $budget->setDeliveryMethod($this->config->getBudgetDeliveryMethod());

        // Create a budget operation.
        $operation = new BudgetOperation();
        $operation->setOperand($budget);
        $operation->setOperator(Operator::ADD);

        // Create the budget on the server.
        $result = $budgetService->mutate([$operation]);
        $budget = $result->getValue()[0];

        // Apply the budget to the campaign object.
        $this->campaignObject->setBudget(new Budget());
        $this->campaignObject->getBudget()->setBudgetId($budget->getBudgetId());
    }

    /**
     * Create the network setting object and apply the settings to the campaign object.
     */
    private function setNetworkTargetingObject() {
        $networkSetting = new NetworkSetting();
        $networkSetting->setTargetGoogleSearch($this->config->getTargetGoogleSearch());
        $networkSetting->setTargetSearchNetwork($this->config->getTargetSearchNetwork());
        $networkSetting->setTargetContentNetwork($this->config->getTargetContentNetwork());

        // Apply the targeting to the campaign object.
        $this->campaignObject->setNetworkSetting($networkSetting);
    }

    /**
     * Create the bidding strategy object and apply the strategy to the campaign object.
     */
    private function setBiddingStrategyObject() {

        // Set bidding strategy (required).
        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBiddingStrategyType($this->config->getBiddingStrategyType());

        // Apply the strategy to the campaign object.
        $this->campaignObject->setBiddingStrategyConfiguration($biddingStrategyConfiguration);
    }

    /**
     * Get campaign object.
     * @return \Google\AdsApi\AdWords\v201609\cm\Campaign
     */
    public function getCampaignObject() {
        return $this->campaignObject;
    }

    /**
     * Set campaign object.
     * @param \Google\AdsApi\AdWords\v201609\cm\Campaign $campaignObject
     * @return Campaign
     */
    public function setCampaignObject($campaignObject) {
        $this->campaignObject = $campaignObject;
        return $this;
    }

    /**
     * Get config.
     * @return CampaignConfig
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Set config.
     * @param CampaignConfig $config
     * @return Campaign
     */
    public function setConfig($config) {
        $this->config = $config;
        return $this;
    }

    /**
     * Get auth object.
     * @return AdWordsAuth
     */
    public function getAuthObject() {
        return $this->authObject;
    }

    /**
     * Set auth object.
     * @param AdWordsAuth $authObject
     * @return Campaign
     */
    public function setAuthObject($authObject) {
        $this->authObject = $authObject;
        return $this;
    }

    /**
     * Get AdWords service.
     * @return AdWordsServices
     */
    public function getAdWordsServices() {
        return $this->adWordsServices;
    }

    /**
     * Set AdWords service.
     * @param AdWordsServices $adWordsServices
     * @return Campaign
     */
    public function setAdWordsServices($adWordsServices) {
        $this->adWordsServices = $adWordsServices;
        return $this;
    }

    /**
     * Get campaign service.
     * @return \Google\AdsApi\Common\AdsSoapClient|\Google\AdsApi\Common\SoapClient
     */
    public function getCampaignService() {
        return $this->campaignService;
    }

    /**
     * Set campaign service.
     * @param \Google\AdsApi\Common\AdsSoapClient|\Google\AdsApi\Common\SoapClient $campaignService
     * @return Campaign
     */
    public function setCampaignService($campaignService) {
        $this->campaignService = $campaignService;
        return $this;
    }

    /**
     * Get campaigns.
     * @return null|array
     */
    public function getCampaigns() {
        return $this->campaigns;
    }

    /**
     * Set campaigns.
     * @param null $campaigns
     * @return Campaign
     */
    public function setCampaigns($campaigns) {
        $this->campaigns = $campaigns;
        return $this;
    }

}