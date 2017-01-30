<?php

namespace EasyAdwords\Campaigns;

use EasyAdwords\Auth\AdWordsAuth;
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
use Google\AdsApi\AdWords\v201609\cm\Selector;

class Campaign {

    protected $operationResult;
    protected $campaignObject;
    protected $config;
    protected $authObject;
    protected $adWordsServices;
    protected $campaignService;
    protected $campaignId;
    protected $campaigns;

    public function __construct(CampaignConfig $config = NULL) {
        if ($config) {
            $this->config = $config;
        } else {
            $this->config = new CampaignConfig([]);
        }

        $this->adWordsServices = new AdWordsServices();

        // Create the auth object.
        $this->authObject = new AdWordsAuth($this->config->getRefreshToken(), $this->config->getAdwordsConfigPath());

        // Build the session with the auth object.
        $this->authObject->buildSession($this->config->getClientCustomerId());

        // Build the campaign service.
        $this->campaignService = $this->adWordsServices->get($this->authObject->getSession(), CampaignService::class);

        // Build the singular campaign object.
        $this->campaignObject = new \Google\AdsApi\AdWords\v201609\cm\Campaign();
        $this->campaigns = NULL;
    }

    /**
     * Create a campaign with given configurations.
     * @throws Exception
     */
    public function create() {

        if (!$this->config->getCampaignName()) {
            throw new Exception("Campaign name must be set in the config object in order to create campaign.");
        }

        // Create a campaign with given settings.
        $this->campaignObject->setName($this->config->getCampaignName());
        $this->campaignObject->setAdvertisingChannelType($this->config->getAdvertisingChannelType());

        // Set shared budget (required).
        $this->createBudget();

        // Set the bidding strategy.
        $this->setBiddingStrategy();

        // Set network targeting.
        $this->setNetworkTargeting();

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
     */
    public function get() {

        // If the campaigns are not already downloaded, download them.
        if (!$this->campaigns) {
            $this->downloadCampaigns();
        }

        return $this->campaigns;
    }

    public function remove() {

        if(!$this->config->getCampaignId()) {
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

    private function downloadCampaigns() {

        // Create selector.
        $selector = new Selector();
        $selector->setFields($this->config->getFields());

        // Set ordering if given in config.
        if ($this->config->getOrdering()) {
            $selector->setOrdering($this->config->getOrdering());
        }

        // Set predicates if given in config.
        if ($this->config->getPredicates()) {
            $selector->setPredicates($this->config->getPredicates());
        }

        // Make the get request.
        $allCampaigns = $this->campaignService->get($selector);

        // Get all the campaigns.
        $this->campaigns = $allCampaigns->getEntries();
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
    private function setNetworkTargeting() {
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
    private function setBiddingStrategy() {

        // Set bidding strategy (required).
        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBiddingStrategyType($this->config->getBiddingStrategyType());

        // Apply the strategy to the campaign object.
        $this->campaignObject->setBiddingStrategyConfiguration($biddingStrategyConfiguration);
    }

    /**
     * @return CampaignConfig
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * @param CampaignConfig $config
     * @return Campaign
     */
    public function setConfig($config) {
        $this->config = $config;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOperationResult() {
        return $this->operationResult;
    }

    /**
     * @param mixed $operationResult
     * @return Campaign
     */
    public function setOperationResult($operationResult) {
        $this->operationResult = $operationResult;
        return $this;
    }
}