<?php

namespace EasyAdwords\Reports;

use Google\AdsApi\AdWords\v201609\cm\ReportDefinitionReportType;

class CampaignPerformanceReport extends Report implements ReportInterface {

    public function __construct(ReportConfig $config) {
        parent::__construct($config);
    }

    public function download() {
        $this->downloadRawReport(ReportDefinitionReportType::CAMPAIGN_PERFORMANCE_REPORT);
        return $this;
    }

    public function format() {
        $this->formatRawReport();
        return $this;
    }
}