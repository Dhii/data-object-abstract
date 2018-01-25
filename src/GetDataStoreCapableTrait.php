<?php

namespace Dhii\Data\Object;

use ArrayObject;

/**
 * Functionality for retrieval of the data store.
 *
 * @since [*next-version*]
 */
trait GetDataStoreCapableTrait
{
    /**
     * The data store.
     *
     * @since [*next-version*]
     *
     * @var ArrayObject|null
     */
    protected $dataStore;

    /**
     * Retrieves a pointer to the data store.
     *
     * @since [*next-version*]
     *
     * @return ArrayObject The data store.
     */
    protected function _getDataStore()
    {
        return $this->dataStore === null
                ? $this->dataStore = new ArrayObject()
                : $this->dataStore;
    }
}
