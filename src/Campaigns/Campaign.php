<?php

namespace EasyAdwords\Campaigns;

use EasyAdwords\Auth\AdWordsAuth;
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

class Campaign {

    protected $config;
    protected $result;

    public function __construct(CampaignConfig $config) {
        $this->config = $config;
    }

    public function create() {

        // Create the auth object.
        $authObject = new AdWordsAuth($this->config->getRefreshToken(), $this->config->getAdwordsConfigPath());

        // Build the session with the auth object.
        $authObject->buildSession($this->config->getClientCustomerId());

        $adWordsServices = new AdWordsServices();
        $budgetService = $adWordsServices->get($authObject->getSession(), BudgetService::class);

        // Create the shared budget (required).
        $money = new Money();
        $money->setMicroAmount($this->config->getBudget() * 1000000);
        $budget = new Budget();
        $budget->setName($this->config->getBudgetName());
        $budget->setAmount($money);
        $budget->setDeliveryMethod($this->config->getBudgetDeliveryMethod());

        $operations = [];

        // Create a budget operation.
        $operation = new BudgetOperation();
        $operation->setOperand($budget);
        $operation->setOperator(Operator::ADD);
        $operations[] = $operation;

        // Create the budget on the server.
        $result = $budgetService->mutate($operations);
        $budget = $result->getValue()[0];

        $campaignService = $adWordsServices->get($authObject->getSession(), CampaignService::class);

        $operations = [];

        // Create a campaign with required and optional settings.
        $campaign = new \Google\AdsApi\AdWords\v201609\cm\Campaign();
        $campaign->setName($this->config->getCampaignName());
        $campaign->setAdvertisingChannelType($this->config->getAdvertisingChannelType());

        // Set shared budget (required).
        $campaign->setBudget(new Budget());
        $campaign->getBudget()->setBudgetId($budget->getBudgetId());

        // Set bidding strategy (required).
        $biddingStrategyConfiguration = new BiddingStrategyConfiguration();
        $biddingStrategyConfiguration->setBiddingStrategyType($this->config->getBiddingStrategyType());
        $campaign->setBiddingStrategyConfiguration($biddingStrategyConfiguration);

        // Set network targeting.
        $networkSetting = new NetworkSetting();
        $networkSetting->setTargetGoogleSearch($this->config->getTargetGoogleSearch());
        $networkSetting->setTargetSearchNetwork($this->config->getTargetSearchNetwork());
        $networkSetting->setTargetContentNetwork($this->config->getTargetContentNetwork());
        $campaign->setNetworkSetting($networkSetting);

        // Set additional settings (optional).
        $campaign->setStatus($this->config->getStatus());
        $campaign->setStartDate($this->config->getStartDate());
        $campaign->setEndDate($this->config->getEndDate());
        $campaign->setAdServingOptimizationStatus($this->config->getAdServingOptimizationStatus());

        // Create a campaign operation and add it to the operations list.
        $operation = new CampaignOperation();
        $operation->setOperand($campaign);
        $operation->setOperator(Operator::ADD);

        $operations[] = $operation;

        // Create the campaigns on the server.
        $result = $campaignService->mutate($operations);
        $this->result = $result->getValue();
    }
}