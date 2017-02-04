<?php

namespace EasyAdWords;


/**
 * Interface for different entities to implement.
 *
 * Interface EntityInterface
 * @package EasyAdWords
 */
interface EntityInterface {

    /**
     * Create the entity.
     * @return mixed
     */
    public function create();

    /**
     * Get the entities.
     * @return mixed
     */
    public function get();

    /**
     * Remove the entity.
     * @return mixed
     */
    public function remove();

    /**
     * Download the entity list from Google.
     * @return mixed
     */
    public function downloadFromGoogle();

    /**
     * Set the config object.
     * @param $config
     * @return mixed
     */
    public function setConfig($config);

    /**
     * Get the config object.
     * @return mixed
     */
    public function getConfig();
}