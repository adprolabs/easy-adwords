<?php

namespace EasyAdWords;

/**
 * Class Config
 * @package EasyAdWords
 */
class Config extends Base {

    /**
     * @var string                   Refresh token of the user - required.
     */
    protected $refreshToken;

    /**
     * @var string                  The client customer ID of the account - required.
     */
    protected $clientCustomerId;

    /**
     * @var string                  Path to the AdWords configuration file. If not given, looks for 'adsapi_php.ini' in the project root.
     */
    protected $adwordsConfigPath;

    /**
     * @var array                   Fields for the operation.
     */
    protected $fields;

    /**
     * @var array                   The predicates array, must be an array of "Predicate" objects.
     */
    protected $predicates;

    /**
     * @var array                   The ordering array, must be an array of "OrderBy" objects.
     */
    protected $ordering;

    /**
     * @var boolean                 The boolean variable to determine if the request will be paginated.
     */
    protected $paginated;

    /**
     * @var integer                 The page size for the paginated request.
     */
    protected $pageSize;

    /**
     * Config constructor.
     * @param array $config
     * @throws \Exception
     */
    public function __construct(array $config) {

        $this->refreshToken = NULL;
        $this->clientCustomerId = NULL;
        $this->adwordsConfigPath = NULL;
        $this->ordering = NULL;
        $this->paginated = false;
        $this->pageSize = self::PAGE_SIZE;

        if (isset($config['refreshToken'])) {
            $this->refreshToken = $config['refreshToken'];
        } else {
            throw new \Exception("Refresh token must be set in config array.");
        }

        if (isset($config['clientCustomerId'])) {
            $this->clientCustomerId = $config['clientCustomerId'];
        } else {
            throw new \Exception("Client customer ID must be set in config array.");
        }

        if (isset($config['adwordsConfigPath'])) {
            $this->adwordsConfigPath = $config['adwordsConfigPath'];
        }

        if (isset($config['fields'])) {
            $this->fields = $config['fields'];
        }

        if (isset($config['predicates'])) {
            if (!is_array($config['predicates'])) {
                $config['predicates'] = [$config['predicates']];
            }

            $this->predicates = $config['predicates'];
        }

        if (isset($config['ordering'])) {
            if (!is_array($config['ordering'])) {
                $config['ordering'] = [$config['ordering']];
            }

            $this->predicates = $config['ordering'];
        }

        if (isset($config['isPaginated']) AND $config['isPaginated'] == true) {
            $this->paginated = $config['isPaginated'];
        }

        if (isset($config['pageSize'])) {
            $this->pageSize = $config['pageSize'];
        }
    }


    /**
     * Get the refresh token.
     * @return string
     */
    public function getRefreshToken() {
        return $this->refreshToken;
    }

    /**
     * Set the refresh token.
     * @param string $refreshToken
     * @return Config
     */
    public function setRefreshToken($refreshToken) {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * Get the client customer ID.
     * @return string
     */
    public function getClientCustomerId() {
        return $this->clientCustomerId;
    }

    /**
     * Set the client customer ID.
     * @param string $clientCustomerId
     * @return Config
     */
    public function setClientCustomerId($clientCustomerId) {
        $this->clientCustomerId = $clientCustomerId;
        return $this;
    }

    /**
     * Get the AdWords config file path.
     * @return mixed
     */
    public function getAdwordsConfigPath() {
        return $this->adwordsConfigPath;
    }

    /**
     * Set the AdWords config file path.
     * @param mixed $adwordsConfigPath
     * @return Config
     */
    public function setAdwordsConfigPath($adwordsConfigPath) {
        $this->adwordsConfigPath = $adwordsConfigPath;
        return $this;
    }

    /**
     * Set the fields.
     * @return mixed
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * Get the fields.
     * @param mixed $fields
     * @return Config
     */
    public function setFields($fields) {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Get the predicates.
     * @return array|mixed
     */
    public function getPredicates() {
        return $this->predicates;
    }

    /**
     * Set the predicates.
     * @param array|mixed $predicates
     * @return Config
     */
    public function setPredicates($predicates) {
        $this->predicates = $predicates;
        return $this;
    }

    /**
     * Add an individual predicate to predicates.
     * @param $predicate
     */
    public function addPredicate($predicate) {
        $this->predicates[] = $predicate;
    }

    /**
     * Get the ordering.
     * @return mixed
     */
    public function getOrdering() {
        return $this->ordering;
    }

    /**
     * Set the ordering.
     * @param mixed $ordering
     * @return Config
     */
    public function setOrdering($ordering) {
        $this->ordering = $ordering;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isPaginated() {
        return $this->paginated;
    }

    /**
     * @param boolean $paginated
     * @return Config
     */
    public function setPaginated($paginated) {
        $this->paginated = $paginated;
        return $this;
    }

    /**
     * @return int
     */
    public function getPageSize() {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     * @return Config
     */
    public function setPageSize($pageSize) {
        $this->pageSize = $pageSize;
        return $this;
    }

}