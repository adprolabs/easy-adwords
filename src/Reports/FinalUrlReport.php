<?php

namespace EasyAdWords\Reports;

use Google\AdsApi\AdWords\v201802\cm\ReportDefinitionReportType;

/**
 * Report class that deals with Final URL Report.
 * Class FinalUrlReport
 * @package EasyAdWords\Reports
 */
class FinalUrlReport extends Report implements ReportInterface {

    protected $reportType = ReportDefinitionReportType::FINAL_URL_REPORT;

    /**
     * FinalUrlReport constructor.
     * @param ReportConfig $config
     */
    public function __construct(ReportConfig $config) {
        parent::__construct($config, $this->reportType);
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