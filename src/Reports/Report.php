<?php

namespace EasyAdWords\Reports;

use EasyAdWords\Auth\AdWordsAuth;
use EasyAdWords\Base;
use Google\AdsApi\AdWords\Reporting\v201609\DownloadFormat;
use Google\AdsApi\AdWords\Reporting\v201609\ReportDefinition;
use Google\AdsApi\AdWords\Reporting\v201609\ReportDefinitionDateRangeType;
use Google\AdsApi\AdWords\Reporting\v201609\ReportDownloader;
use Google\AdsApi\AdWords\v201609\cm\DateRange;
use Google\AdsApi\AdWords\v201609\cm\Selector;

/**
 * Base report class that deals with different report types.
 * Class Report
 * @package EasyAdWords\Reports
 */
class Report extends Base {

    /**
     * @var ReportConfig            Config object of the report.
     */
    protected $config;

    /**
     * @var string|array            The downloaded and formatted report result.
     */
    protected $report;

    /**
     * @var array                   The headers of the report.
     */
    protected $reportHeaders;

    /**
     * Report constructor.
     * @param ReportConfig $config
     */
    public function __construct(ReportConfig $config) {
        $this->config = $config;
        return $this;
    }

    /**
     * Downloads the raw report as string.
     * @param $reportType
     * @return $this
     */
    protected function downloadRawReport($reportType) {

        // Create the auth object.
        $authObject = new AdWordsAuth($this->config->getRefreshToken(), $this->config->getAdwordsConfigPath());

        // Build the session with the auth object.
        $authObject->buildSession($this->config->getClientCustomerId());

        // Create report date range object.
        $reportDates = new DateRange($this->config->getStartDate(), $this->config->getEndDate());

        // Create selector.
        $selector = new Selector();
        $selector->setFields($this->config->getFields());
        $selector->setDateRange($reportDates);

        if (!empty($this->config->getPredicates())) {
            $selector->setPredicates($this->config->getPredicates());
        }

        // Create report definition.
        $reportDefinition = new ReportDefinition();
        $reportDefinition->setSelector($selector);
        $reportDefinition->setReportName('AdProReport');
        $reportDefinition->setDateRangeType(ReportDefinitionDateRangeType::CUSTOM_DATE);
        $reportDefinition->setReportType($reportType);
        $reportDefinition->setDownloadFormat(DownloadFormat::CSV);

        // Download report.
        $reportDownloader = new ReportDownloader($authObject->getSession());
        $reportDownloadResult = $reportDownloader->downloadReport($reportDefinition);

        // Save the string version.
        $this->report = $reportDownloadResult->getAsString();
        return $this;
    }

    /**
     * Formats the report into a simple one-dimensional array.
     * Works directly on the report object for memory optimization.
     * @return $this
     */
    public function formatRawReport() {

        // Get the rows as array and split the headers from the report.
        $this->report = explode(PHP_EOL, $this->report);
        $this->reportHeaders = str_getcsv(array_shift($this->report));

        // Convert each line of the CSV to array.
        foreach ($this->report as $key => $item) {
            if ($item !== '') {
                $this->report[$key] = str_getcsv($item);
            } else {
                unset($this->report[$key]);
            }
        }

        return $this;
    }

    /**
     * Get report config.
     * @return ReportConfig
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Set report config.
     * @param ReportConfig $config
     * @return Report
     */
    public function setConfig($config) {
        $this->config = $config;
        return $this;
    }

    /**
     * Get the report result.
     * @return array|string
     */
    public function getReport() {
        return $this->report;
    }

    /**
     * Set the report result.
     * @param array|string $report
     * @return Report
     */
    public function setReport($report) {
        $this->report = $report;
        return $this;
    }

    /**
     * Get the report headers.
     * @return array
     */
    public function getReportHeaders() {
        return $this->reportHeaders;
    }

    /**
     * Set the report headers.
     * @param array $reportHeaders
     * @return Report
     */
    public function setReportHeaders($reportHeaders) {
        $this->reportHeaders = $reportHeaders;
        return $this;
    }

}