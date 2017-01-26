<?php

namespace EasyAdwords\Reports;

interface ReportInterface {
    public function setConfig($config);
    public function getConfig();
    public function download();
    public function format();
}