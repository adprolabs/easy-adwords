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
use Google\AdsApi\AdWords\v201609\cm\Money;
use Google\AdsApi\AdWords\v201609\cm\NetworkSetting;
use Google\AdsApi\AdWords\v201609\cm\Operator;
use Google\AdsApi\AdWords\v201609\cm\OrderBy;
use Google\AdsApi\AdWords\v201609\cm\Paging;
use Google\AdsApi\AdWords\v201609\cm\Predicate;
use Google\AdsApi\AdWords\v201609\cm\PredicateOperator;
use Google\AdsApi\AdWords\v201609\cm\Selector;
use Google\AdsApi\AdWords\v201609\cm\ServingStatus;
use Google\AdsApi\AdWords\v201609\cm\SortOrder;

class Campaign {

    protected $result;
    protected $campaign;
    protected $config;
    protected $authObject;
    protected $adWordsServices;
    protected $campaignService;
    protected $campaignId;

    public function __construct(CampaignConfig $config) {
        $this->config = $config;
        $this->adWordsServices = new AdWordsServices();

        // Create the auth object.
        $this->authObject = new AdWordsAuth($this->config->getRefreshToken(), $this->config->getAdwordsConfigPath());

        // Build the session with the auth object.
        $this->authObject->buildSession($this->config->getClientCustomerId());

        // Build the campaign service.
        $this->campaignService = $this->adWordsServices->get($this->authObject->getSession(), CampaignService::class);

        // Build the singular campaign object.
        $this->campaign = new \Google\AdsApi\AdWords\v201609\cm\Campaign();

    }

    /**
     * Create a campaign with given configurations.
     * @throws Exception
     */
    public function create() {

        if (!$this->config->getCampaignName()) {
            throw new Exception("Campaign name must be set to create campaign.");
        }

        // Create a campaign with given settings.
        $this->campaign->setName($this->config->getCampaignName());
        $this->campaign->setAdvertisingChannelType($this->config->getAdvertisingChannelType());

        // Set shared budget (required).
        $this->createBudget();

        // Set the bidding strategy.
        $this->setBiddingStrategy();

        // Set network targeting.
        $this->setNetworkTargeting();

        // Set additional settings (optional).
        $this->campaign->setStatus($this->config->getStatus());
        $this->campaign->setStartDate($this->config->getStartDate());
        $this->campaign->setEndDate($this->config->getEndDate());
        $this->campaign->setAdServingOptimizationStatus($this->config->getAdServingOptimizationStatus());
        $this->campaign->setServingStatus($this->config->getServingStatus());

        // Create a campaign operation and add it to the operations list.
        $operation = new CampaignOperation();
        $operation->setOperand($this->campaign);
        $operation->setOperator(Operator::ADD);

        // Create the campaigns on the server.
        $result = $this->campaignService->mutate([$operation]);
        $this->result = $result->getValue();
    }

    /**
     * List all the campaigns with the given fields and predicates.
     */
    public function all() {

        // Create selector.
        $selector = new Selector();
        $selector->setFields($this->config->getFields());
        $selector->setOrdering([new OrderBy('Name', SortOrder::ASCENDING)]);

        if($this->config->getCampaignId()) {
            $selector->setPredicates([new Predicate('Id', PredicateOperator::EQUALS, $this->config->getCampaignId())]);
        }


        // Make the get request.
        $allCampaigns = $this->campaignService->get($selector);

        // Get all the campaigns.
        $this->result = $allCampaigns->getEntries();
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
        $this->campaign->setBudget(new Budget());
        $this->campaign->getBudget()->setBudgetId($budget->getBudgetId());
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
        $this->campaign->setNetworkSetting($networkSetting);
    }

    /**
     * Create the bidding strategy object and apply the strategy to the campaign object.
     */
    private function setBiddingStrategy() {

        // Set bidding strategy (required).
        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBiddingStrategyType($this->config->getBiddingStrategyType());

        // Apply the strategy to the campaign object.
        $this->campaign->setBiddingStrategyConfiguration($biddingStrategyConfiguration);
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
    public function getResult() {
        return $this->result;
    }

    /**
     * @param mixed $result
     * @return Campaign
     */
    public function setResult($result) {
        $this->result = $result;
        return $this;
    }
}