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
     */
    public function __construct(ReportConfig $config) {
        parent::__construct($config);
    }

    /**
     * Download the raw CSV report from AdWords and store in the object.
     * @param null $reportType
     * @return $this
     */
    public function download($reportType = NULL) {
        $this->downloadRawReport($reportType);
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