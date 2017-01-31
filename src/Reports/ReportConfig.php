<?php

namespace EasyAdwords\Reports;

use EasyAdwords\Config;

class ReportConfig extends Config {

    protected $dateStart;
    protected $dateEnd;
    protected $predicates;

    public function __construct(array $config = array()) {

        parent::__construct($config);

        $this->fields = array();

        if (isset($config['dateStart'])) {
            $this->dateStart = $config['dateStart'];
        }

        if (isset($config['dateEnd'])) {
            $this->dateEnd = $config['dateEnd'];
        }
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


}