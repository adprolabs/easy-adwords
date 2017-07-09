<?php

namespace EasyAdWords\Reports;

use Google\AdsApi\AdWords\v201705\cm\ReportDefinitionReportType;

/**
 * Class SearchQueryPerformanceReport
 * @package EasyAdWords\Reports
 */
class SearchQueryPerformanceReport extends Report implements ReportInterface {

    protected $reportType = ReportDefinitionReportType::SEARCH_QUERY_PERFORMANCE_REPORT;

    /**
     * SearchQueryPerformanceReport constructor.
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