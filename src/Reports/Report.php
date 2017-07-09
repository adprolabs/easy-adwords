<?php

namespace EasyAdWords\Reports;

use EasyAdWords\AdWordsAuth\AdWordsAuth;
use EasyAdWords\Base;
use Google\AdsApi\AdWords\Reporting\v201705\DownloadFormat;
use Google\AdsApi\AdWords\Reporting\v201705\ReportDefinition;
use Google\AdsApi\AdWords\Reporting\v201705\ReportDefinitionDateRangeType;
use Google\AdsApi\AdWords\Reporting\v201705\ReportDownloader;
use Google\AdsApi\AdWords\v201705\cm\DateRange;
use Google\AdsApi\AdWords\v201705\cm\Selector;

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
     * @var
     */
    protected $reportType;

    /**
     * @var string|array            The downloaded and formatted report result.
     */
    protected $report;

    /**
     * @var array                   The headers of the report.
     */
    protected $reportHeaders;

    /**
     * @var string                  The path to save the report.
     */
    protected $filePath;

    /**
     * Report constructor.
     * @param ReportConfig $config
     * @param $reportType
     */
    public function __construct(ReportConfig $config, $reportType) {
        $this->config = $config;
        $this->reportType = $reportType;

        return $this;
    }

    /**
     * Downloads the raw report as string.
     * @return $this
     */
    protected function downloadRawReport() {

        // Get the report object from AdWords.
        $reportDownloadResult = $this->downloadReportFromAdWords($this->reportType);

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
     * Saves the report CSV to a given file.
     * @param $filePath
     * @return $this
     */
    public function saveToFile($filePath) {
        $this->file_force_contents($filePath, $this->report);
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Download report from AdWords and write it to the given file.
     * However, it does not store the report, just writes the contents to the file.
     * @param $filePath
     */
    public function downloadToFile($filePath) {

        // Create the missing folders in the file path.
        $this->createMissingFolders($filePath);

        // Get the report object from AdWords.
        $reportDownloadResult = $this->downloadReportFromAdWords($this->reportType);
        $reportDownloadResult->saveToFile($filePath);
    }

    /**
     * Downloads the result from Google AdWords.
     * @param $reportType
     * @return \Google\AdsApi\AdWords\Reporting\ReportDownloadResult
     */
    private function downloadReportFromAdWords($reportType) {
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

        return $reportDownloader->downloadReport($reportDefinition);
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

    /**
     * @return mixed
     */
    public function getFilePath() {
        return $this->filePath;
    }

    /**
     * @param mixed $filePath
     * @return Report
     */
    public function setFilePath($filePath) {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getReportType() {
        return $this->reportType;
    }

    /**
     * @param mixed $reportType
     * @return Report
     */
    public function setReportType($reportType) {
        $this->reportType = $reportType;

        return $this;
    }

    /**
     * An alias to the getReport method.
     * @return array|string
     */
    public function get() {
        return $this->report;
    }

    /**
     * Creates required directories if they are not set.
     * Taken from http://php.net/manual/en/function.file-put-contents.php#84180
     * @param $filePath
     * @param $contents
     */
    private function file_force_contents($filePath, $contents) {
        $this->createMissingFolders($filePath);
        file_put_contents($filePath, $contents);
    }

    private function createMissingFolders($path) {
        $directories = explode('/', $path);
        $fileName = array_pop($directories);
        $path = implode('/', $directories);
        if (!is_dir($path)) {
            mkdir($path, 0, true);
        }
    }

}