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
            $key = $this->_normalizeString($key);
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
     * Normalizes a value to its string representation.
     *
     * The values that can be normalized are any scalar values, as well as
     * {@see StringableInterface).
     *
     * @since [*next-version*]
     *
     * @param Stringable|string|int|float|bool $subject The value to normalize to string.
     *
     * @throws InvalidArgumentException If the value cannot be normalized.
     *
     * @return string The string that resulted from normalization.
     */
    abstract protected function _normalizeString($subject);
}
