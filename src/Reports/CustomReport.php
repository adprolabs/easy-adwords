<?php

namespace EasyAdWords\Reports;

/**
 * Report class that deals with custom report needs.
 * Class FinalUrlReport
 * @package EasyAdWords\Reports
 */
class CustomReport extends Report implements ReportInterface {

    /**
     * FinalUrlReport constructor.
     * @param ReportConfig $config
     * @param null $reportType
     */
    public function __construct(ReportConfig $config, $reportType = null) {
        parent::__construct($config, $reportType);
    }

    /**
     * Download the raw CSV report from AdWords and store in the object.
     * @return $this
     */
    public function download() {
        $this->downloadRawReport();

        return $this;
    }

    /**
     * Format the raw CSV report into a flat array.
     * @return $this
     */
    public function format() {
        $this->formatRawReport();

        return $this;
    }
}