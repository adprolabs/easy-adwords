<?php

namespace App\AdPro\Reports;

use Google\AdsApi\AdWords\v201609\cm\ReportDefinitionReportType;

class SearchQueryPerformanceReport extends AdProReport implements ReportInterface {

    public function __construct(ReportConfig $config) {
        parent::__construct($config);
    }

    public function download() {
        $this->downloadRawReport(ReportDefinitionReportType::SEARCH_QUERY_PERFORMANCE_REPORT);
        return $this;
    }

    public function format() {
        $this->formatRawReport();
        return $this;
    }
}