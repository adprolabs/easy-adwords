<?php

namespace EasyAdWords;


use EasyAdWords\Auth\AdWordsAuth;
use Google\AdsApi\AdWords\AdWordsServices;
use Google\AdsApi\AdWords\v201609\cm\Selector;

/**
 * Parent of the various entity classes, such as Campaign, AdGroup and Keyword.
 *
 * Class Entity
 * @package EasyAdWords
 */
class Entity extends Base {

    const PAGE_LIMIT = 10000;

    /**
     * @var array                   Result of the completed operation.
     */
    protected $operationResult;

    /**
     * @var AdWordsAuth             The auth object for the operation.
     */
    protected $authObject;

    /**
     * @var AdWordsServices         The AdWordsServices object for the operation.
     */
    protected $adWordsServices;

    /**
     * Entity constructor.
     * @param Config $config
     */
    public function __construct(Config $config) {

        $this->adWordsServices = new AdWordsServices();

        // Create the auth object.
        $this->authObject = new AdWordsAuth($config->getRefreshToken(), $config->getAdwordsConfigPath());

        // Build the session with the auth object.
        $this->authObject->buildSession($config->getClientCustomerId());
    }


    /**
     * Download all the entities that meet the given config criteria and service.
     * Useful if the list needs to be re-downloaded.
     * @param Config $config
     * @param $adwordsService
     * @return
     */
    public function downloadFromGoogle(Config $config, $adwordsService) {

        // Create a selector to select all ad groups for the specified campaign.
        $selector = new Selector();
        $selector->setFields($config->getFields());

        // Set ordering if given in config.
        if ($config->getOrdering()) {
            $selector->setOrdering($config->getOrdering());
        }

        // Set predicates if given in config.
        if ($config->getPredicates()) {
            $selector->setPredicates($config->getPredicates());
        }

        // Set the pagination for the request.
        $selector->setPaging(new Paging(0, self::PAGE_LIMIT));
        $result = array();

        do {
          $page = $adWordsServices->get($selector);

          // Get the page entries and add them to the result array.
          if ($page->getEntries() !== null) {
            $totalNumEntries = $page->getTotalNumEntries();
            $result = array_merge($result, $page->getEntries());
          }

          $selector->getPaging()->setStartIndex(
              $selector->getPaging()->getStartIndex() + self::PAGE_LIMIT);
        } while ($selector->getPaging()->getStartIndex() < $totalNumEntries);

        return $result;
    }


    /**
     * Get the operation result.
     * @return mixed
     */
    public function getOperationResult() {
        return $this->operationResult;
    }

    /**
     * Set the operation result.
     * @param mixed $operationResult
     * @return Entity
     */
    public function setOperationResult($operationResult) {
        $this->operationResult = $operationResult;
        return $this;
    }

    /**
     * Get the auth object.
     * @return AdWordsAuth
     */
    public function getAuthObject() {
        return $this->authObject;
    }

    /**
     * Set the auth object.
     * @param AdWordsAuth $authObject
     * @return Entity
     */
    public function setAuthObject($authObject) {
        $this->authObject = $authObject;
        return $this;
    }

    /**
     * Get the AdWordsServices object.
     * @return AdWordsServices
     */
    public function getAdWordsServices() {
        return $this->adWordsServices;
    }

    /**
     * Set the AdWordsServices object.
     * @param AdWordsServices $adWordsServices
     * @return Entity
     */
    public function setAdWordsServices($adWordsServices) {
        $this->adWordsServices = $adWordsServices;
        return $this;
    }
}