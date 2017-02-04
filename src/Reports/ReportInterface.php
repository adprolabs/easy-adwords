<?php

namespace EasyAdWords\Reports;

use EasyAdWords\Config;

/**
 * Interface for the different report objects to implement.
 * Interface ReportInterface
 * @package EasyAdWords\Reports
 */
interface ReportInterface {

    /**
     * Set the config object.
     * @param $config
     * @return Config
     */
    public function setConfig($config);

    /**
     * Get the config object.
     * @return Config
     */
    public function getConfig();

    /**
     * Download the report as string.
     * @return mixed
     */
    public function download();

    /**
     * Format the report into a flat array.
     * @return mixed
     */
    public function format();
}