<?php

namespace EasyAdWords\Reports;

use EasyAdWords\Config;
use Exception;

/**
 * Report config class to use with report objects.
 *
 * Class ReportConfig
 * @package EasyAdWords\Reports
 */
class ReportConfig extends Config {

    /**
     * @var string              Start date of the report.
     */
    protected $startDate;
    /**
     * @var string              End date of the report.
     */
    protected $endDate;
    /**
     * @var array               Array of predicates to filter the report.
     */
    protected $predicates;

    /**
     * ReportConfig constructor.
     * @param array $config
     */
    public function __construct(array $config = array()) {

        parent::__construct($config);

        if($this->fields === null) {
            throw new Exception("Fields must be set for getting a report.");
        }

        if (isset($config['startDate'])) {
            $this->startDate = $config['startDate'];
        }

        if (isset($config['endDate'])) {
            $this->endDate = $config['endDate'];
        }
    }

    /**
     * Get the start date of the report.
     * @return null
     */
    public function getStartDate() {
        return $this->startDate;
    }

    /**
     * Set the start date of the report.
     * @param null $startDate
     * @return $this
     */
    public function setStartDate($startDate) {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * Get the end date of the report.
     * @return null
     */
    public function getEndDate() {
        return $this->endDate;
    }

    /**
     * Set the start date of the report.
     * @param null $endDate
     * @return $this
     */
    public function setEndDate($endDate) {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Get the report predicates.
     * @return array
     */
    public function getPredicates() {
        return $this->predicates;
    }

    /**
     * Set the report predicates.
     * @param array $predicates
     * @return ReportConfig
     */
    public function setPredicates($predicates) {
        $this->predicates = $predicates;
        return $this;
    }
}