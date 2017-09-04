<?php

namespace Dhii\Data\Object;

/**
 * Functionality for retrieval of the data store.
 *
 * @since [*next-version*]
 */
trait GetDataStoreCapableTrait
{
    protected $dataStore;

    /**
     * Retrieves a pointer to the data store.
     *
     * @since [*next-version*]
     *
     * @return object The data store.
     */
    protected function _getDataStore()
    {
        return $this->dataStore === null
                ? $this->dataStore = new \stdClass()
                : $this->dataStore;
    }
}
