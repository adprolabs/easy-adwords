<?php

namespace App\AdPro\Reports;

use EasyAdwords\Reports\Report;
use EasyAdwords\Reports\ReportConfig;
use EasyAdwords\Reports\ReportInterface;
use Google\AdsApi\AdWords\v201609\cm\ReportDefinitionReportType;

/**
 * Report class that deals with Search Query Performance Report.
 * Class SearchQueryPerformanceReport
 * @package App\AdPro\Reports
 */
class SearchQueryPerformanceReport extends Report implements ReportInterface {

    public function __construct(ReportConfig $config) {
        parent::__construct($config);
    }

    /**
     * Download the raw CSV report from AdWords and store in the object.
     * @return $this
     */
    public function download() {
        $this->downloadRawReport(ReportDefinitionReportType::SEARCH_QUERY_PERFORMANCE_REPORT);
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