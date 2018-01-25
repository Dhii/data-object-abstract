<?php

namespace Dhii\Data\Object;

use ArrayAccess;
use OutOfBoundsException;
use Psr\Container\ContainerInterface;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;

/**
 * Functionality for data retrieval.
 *
 * @since [*next-version*]
 */
trait UnsetDataCapableTrait
{
    /**
     * Unset data by key.
     *
     * @since [*next-version*]
     *
     * @param string|int|float|bool|Stringable $key The key of data to unset.
     *
     * @throws OutOfBoundsException If the key does not exist.
     */
    protected function _unsetData($key)
    {
        $key   = $this->_normalizeKey($key);
        $store = $this->_getDataStore();

        if (!$store->offsetExists($key)) {
            throw $this->_createOutOfBoundsException(
                $this->__('Data key does not exist'),
                null,
                null,
                $key
            );
        }

        $store->offsetUnset($key);
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
     * Creates a new Out Of Bounds exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     * @param mixed|null             $argument The invalid argument, if any.
     *
     * @return OutOfBoundsException The new exception.
     */
    abstract protected function _createOutOfBoundsException(
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
