<?php

namespace Dhii\Data\Object;

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
     * @param string|Stringable $key The key, for which to check the data.
     *
     * @return bool True if data for the specified key exists; false otherwise.
     */
    protected function _hasData($key)
    {
        if (!is_null($key)) {
            $key = $this->_normalizeKey($key);
        }

        $store = $this->_getDataStore();
        $key   = (string) $key;

        return property_exists($store, $key);
    }

    /**
     * Retrieves a pointer to the data store.
     *
     * @since [*next-version*]
     *
     * @return object The data store.
     */
    abstract protected function _getDataStore();

    /**
     * Translates a string, and replaces placeholders.
     *
     * @since [*next-version*]
     * @see sprintf()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);

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
