<?php

namespace Dhii\Data\Object;

use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerInterface;

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
            $this->_throwNotFoundException($this->__('Data key not found'), null, null, null, $key);
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
     * Throws a Not Found exception.
     *
     * @param string|Stringable|null     $message   The exception message, if any.
     * @param int|string|Stringable|null $code      The numeric exception code, if any.
     * @param RootException|null         $previous  The inner exception, if any.
     * @param ContainerInterface|null    $container The associated container, if any.
     * @param string|Stringable|null     $dataKey   The missing data key, if any.
     *
     * @since [*next-version*]
     *
     * @throws NotFoundExceptionInterface
     */
    abstract protected function _throwNotFoundException(
        $message = null,
        $code = null,
        RootException $previous = null,
        ContainerInterface $container = null,
        $dataKey = null
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
