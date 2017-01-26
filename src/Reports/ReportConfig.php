<?php

namespace EasyAdwords\Reports;

use EasyAdwords\Config;

class ReportConfig extends Config {

    protected $fields;
    protected $dateStart;
    protected $dateEnd;
    protected $predicates;

    public function __construct(array $config = array()) {

        parent::__construct($config);

        $this->predicates = array();
        $this->fields = array();

        if (isset($config['fields'])) {
            $this->fields = $config['fields'];
        }

        if (isset($config['dateStart'])) {
            $this->dateStart = $config['dateStart'];
        }

        if (isset($config['dateEnd'])) {
            $this->dateEnd = $config['dateEnd'];
        }

        if (isset($config['predicates'])) {
            if (!is_array($config['predicates'])) {
                $config['predicates'] = [$config['predicates']];
            }

            $this->predicates = $config['predicates'];
        }
    }

    /**
     * @param array $fields
     * @return ReportConfig
     */
    public function setFields($fields) {
        $this->fields = $fields;
    }

    /**
     * @return array
     */
    public function getFields() {
        return $this->fields;
    }

    /**
     * @return null
     */
    public function getDateStart() {
        return $this->dateStart;
    }

    /**
     * @param null $dateStart
     */
    public function setDateStart($dateStart) {
        $this->dateStart = $dateStart;
    }

    /**
     * @return null
     */
    public function getDateEnd() {
        return $this->dateEnd;
    }

    /**
     * @param null $dateEnd
     */
    public function setDateEnd($dateEnd) {
        $this->dateEnd = $dateEnd;
    }

    /**
     * @return mixed
     */
    public function getPredicates() {
        return $this->predicates;
    }

    /**
     * @param mixed $predicates
     */
    public function setPredicates($predicates) {
        $this->predicates = $predicates;
    }

    /**
     * @param $predicate
     */
    public function addPredicate($predicate) {
        $this->predicates[] = $predicate;
    }
}