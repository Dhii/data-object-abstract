<?php

namespace Dhii\Data\Object;

use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Functionality for data retrieval.
 *
 * @since [*next-version*]
 */
trait GetDataCapableTrait
{
    /**
     * Retrieve data, all or by key.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $key The key, for which to get the data,
     *                                    or null to get all data as an array.
     *
     * @return mixed|array The value for the specified key, or all data as an key-value map.
     */
    protected function _getData($key = null)
    {
        if (!is_null($key) && !is_string($key) && !($key instanceof Stringable)) {
            throw $this->_createInvalidArgumentException($this->__('Data key must be stringable'), null, null, $key);
        }

        $store = $this->_getDataStore();

        // Return whole set
        if (is_null($key)) {
            return (array) $store;
        }

        $key = (string) $key;
        if (!property_exists($store, $key)) {
            throw $this->_createNotFoundException($this->__('Data key not found'), $key);
        }

        return $store->{$key};
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
     * Creates a new not found exception.
     *
     * @param string|Stringable|null $message  The message for the exception, if any.
     * @param string|Stringable|null $dataKey  The data key, if any.
     * @param RootException|null     $previous The inner exception, if any.
     *
     * @since [*next-version*]
     *
     * @return NotFoundExceptionInterface The new exception.
     */
    abstract protected function _createNotFoundException($message = null, $dataKey = null, RootException $previous = null);

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
     * @see _translate()
     *
     * @param string $string  The format string to translate.
     * @param array  $args    Placeholder values to replace in the string.
     * @param mixed  $context The context for translation.
     *
     * @return string The translated string.
     */
    abstract protected function __($string, $args = [], $context = null);
}
