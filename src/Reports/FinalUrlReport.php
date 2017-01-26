<?php

namespace EasyAdwords\Reports;

use Google\AdsApi\AdWords\v201609\cm\ReportDefinitionReportType;

class FinalUrlReport extends Report implements ReportInterface {

    public function __construct(ReportConfig $config) {
        parent::__construct($config);
    }

    public function download() {
        $this->downloadRawReport(ReportDefinitionReportType::FINAL_URL_REPORT);
        return $this;
    }

    public function format() {
        $this->formatRawReport();
        return $this;
    }
}