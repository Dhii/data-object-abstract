<?php

namespace Dhii\Data\Object;

use OutOfBoundsException;
use OutOfRangeException;
use stdClass;
use Traversable;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;

/**
 * Functionality for unsetting multiple keys at once.
 *
 * @since [*next-version*]
 */
trait UnsetManyCapableTrait
{
    /**
     * Unset data by keys.
     *
     * @since [*next-version*]
     *
     * @param string[]|int[]|float[]|bool[]|Stringable[]|stdClass|Traversable $keys The keys of data to unset.
     *
     * @throws InvalidArgumentException If the list of keys is invalid.
     * @throws OutOfRangeException      If one of the keys is invalid.
     * @throws OutOfBoundsException     If one of the keys does not exist.
     */
    protected function _unsetMany($keys)
    {
        $keys = $this->_normalizeIterable($keys);

        foreach ($keys as $_idx => $_key) {
            try {
                $this->_unsetData($_key);
            } catch (InvalidArgumentException $e) {
                throw $this->_createOutOfRangeException(
                    $this->__('Tried to unset by an invalid key'),
                    null,
                    $e,
                    $_key
                );
            }
        }
    }

    /**
     * Unset data by key.
     *
     * @since [*next-version*]
     *
     * @param string|int|float|bool|Stringable $key The key of data to unset.
     *
     * @throws OutOfBoundsException If the key does not exist.
     */
    abstract protected function _unsetData($key);

    /**
     * Creates a new Out Of Range exception.
     *
     * @since [*next-version*]
     *
     * @param string|Stringable|null $message  The error message, if any.
     * @param int|null               $code     The error code, if any.
     * @param RootException|null     $previous The inner exception for chaining, if any.
     * @param mixed|null             $argument The invalid argument, if any.
     *
     * @return OutOfRangeException The new exception.
     */
    abstract protected function _createOutOfRangeException(
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
     * Normalizes an iterable.
     *
     * Makes sure that the return value can be iterated over.
     *
     * @since [*next-version*]
     *
     * @param mixed $iterable The iterable to normalize.
     *
     * @throws InvalidArgumentException If the iterable could not be normalized.
     *
     * @return array|Traversable|stdClass The normalized iterable.
     */
    abstract protected function _normalizeIterable($iterable);
}
