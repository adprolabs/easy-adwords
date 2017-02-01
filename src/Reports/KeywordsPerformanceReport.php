<?php

namespace EasyAdwords\Reports;

use Google\AdsApi\AdWords\v201609\cm\ReportDefinitionReportType;

/**
 * Report class that deals with Keywords Performance Report.
 * Class KeywordsPerformanceReport
 * @package EasyAdwords\Reports
 */
class KeywordsPerformanceReport extends Report implements ReportInterface {

    public function __construct(ReportConfig $config) {
        parent::__construct($config);
    }

    /**
     * Download the raw CSV report from AdWords and store in the object.
     * @return $this
     */
    public function download() {
        $this->downloadRawReport(ReportDefinitionReportType::KEYWORDS_PERFORMANCE_REPORT);
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