<?php

namespace EasyAdwords;


interface EntityInterface {
    public function create();
    public function get();
    public function remove();
    public function downloadFromGoogle();
    public function setConfig($config);
    public function getConfig();
}