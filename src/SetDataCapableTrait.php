<?php

namespace Dhii\Data\Object;

use ArrayAccess;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;

/**
 * Functionality for data assignment.
 *
 * @since [*next-version*]
 */
trait SetDataCapableTrait
{
    /**
     * Assign a single piece of data.
     *
     * @since [*next-version*]
     *
     * @param string|int|float|bool|Stringable $key   The key, for which to assign the data.
     *                                                Unless an integer is given, this will be normalized to string.
     * @param mixed                            $value The value to assign.
     *
     * @throws InvalidArgumentException If key is invalid.
     * @throws RootException            If a problem occurs while writing data.
     */
    protected function _setData($key, $value)
    {
        $key   = $this->_normalizeKey($key);
        $store = $this->_getDataStore();
        $store->offsetSet($key, $value);
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
