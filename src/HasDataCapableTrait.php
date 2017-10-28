<?php

namespace Dhii\Data\Object;

use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
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
        if (!is_null($key) && !is_string($key) && !($key instanceof Stringable)) {
            throw $this->_createInvalidArgumentException($this->__('Data key must be stringable'), null, null, $key);
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
     * Creates a new invalid argument exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     * @param mixed|null             $argument The invalid argument, if any.
     *
     * @return InvalidArgumentException The new exception.
     */
    abstract protected function _createInvalidArgumentException(
            $message = null,
            $code = null,
            RootException $previous = null,
            $argument = null
    );

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
}
