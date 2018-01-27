<?php

namespace Dhii\Data\Object;

use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerInterface;
use ArrayAccess;

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
     * @param string|int|float|bool|Stringable $key The key, for which to get the data.
     *                                              Unless an integer is given, this will be normalized to string.
     *
     * @throws InvalidArgumentException If key is invalid.
     *
     * @return mixed The value for the specified key.
     */
    protected function _getData($key)
    {
        $key   = $this->_normalizeKey($key);
        $store = $this->_getDataStore();

        if (!$store->offsetExists($key)) {
            throw $this->_throwNotFoundException($this->__('Data key not found'), null, null, null, $key);
        }

        return $store->offsetGet($key);
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
