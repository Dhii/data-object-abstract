<?php

namespace Dhii\Data\Object;

use ArrayAccess;
use Dhii\Util\String\StringableInterface as Stringable;
use InvalidArgumentException;

/**
 * Functionality for data checking.
 *
 * @since [*next-version*]
 */
trait HasDataCapableTrait
{
    /**
     * Check data by key.
     *
     * @since [*next-version*]
     *
     * @param string|int|float|bool|Stringable $key The key, for which to check the data.
     *                                              Unless an integer is given, this will be normalized to string.
     *
     * @throws InvalidArgumentException If key is invalid.
     *
     * @return bool True if data for the specified key exists; false otherwise.
     */
    protected function _hasData($key)
    {
        $key   = $this->_normalizeKey($key);
        $store = $this->_getDataStore();

        return $store->offsetExists($key);
    }

    /**
     * Retrieves a pointer to the data store.
     *
     * @since [*next-version*]
     *
     * @return ArrayAccess The data store.
     */
    abstract protected function _getDataStore();

    /**
     * Normalizes an array key.
     *
     * If key is not an integer (strict type check), it will be normalized to string.
     * Otherwise it is left as is.
     *
     * @since [*next-version*]
     *
     * @param string|int|float|bool|Stringable $key The key to normalize.
     *
     * @throws InvalidArgumentException If the value cannot be normalized.
     *
     * @return string|int The normalized key.
     */
    abstract protected function _normalizeKey($key);
}
