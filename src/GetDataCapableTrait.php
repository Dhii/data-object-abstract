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
        if (!is_null($key)) {
            $key = $this->_normalizeString($key);
        }

        $store = $this->_getDataStore();

        // Return whole set
        if (is_null($key)) {
            return (array) $store;
        }

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
