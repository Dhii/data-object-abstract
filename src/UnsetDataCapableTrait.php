<?php

namespace Dhii\Data\Object;

use Psr\Container\ContainerInterface;
use Traversable;
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
     * Unset data by keys.
     *
     * @since [*next-version*]
     *
     * @param Stringable[]|Traversable $keys The keys of data to unset.
     */
    protected function _unsetData($keys)
    {
        if (!is_array($keys) && !($keys instanceof Traversable)) {
            throw $this->_createInvalidArgumentException($this->__('Keys must be iterable'), null, null, $keys);
        }

        $store = $this->_getDataStore();
        foreach ($keys as $_idx => $_key) {
            $_key = $this->_normalizeString($_key);

            if (!isset($store->{$_key})) {
                $this->_throwNotFoundException(
                    $this->__('Key not found'),
                    null,
                    null,
                    null,
                    $_key
                );
            }

            unset($store->{$_key});
        }
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
