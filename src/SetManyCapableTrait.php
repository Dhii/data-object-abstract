<?php

namespace Dhii\Data\Object;

use Dhii\Util\String\StringableInterface as Stringable;
use OutOfRangeException;
use stdClass;
use Traversable;
use Exception as RootException;
use InvalidArgumentException;

/**
 * Functionality for data assignment.
 *
 * @since [*next-version*]
 */
trait SetManyCapableTrait
{
    /**
     * Assign data.
     *
     * @since [*next-version*]
     *
     * @param array|stdClass|Traversable $data The data to set. Existing keys will be overwritten.
     *                                         The rest of the data remains unaltered.
     *
     * @throws InvalidArgumentException If the list of keys is invalid.
     * @throws OutOfRangeException      If trying to set data for an invalid key.
     * @throws RootException            If a problem occurs while writing data.
     */
    protected function _setMany($data)
    {
        $data = $this->_normalizeIterable($data);

        foreach ($data as $_key => $_value) {
            try {
                $this->_setData($_key, $_value);
            } catch (InvalidArgumentException $e) {
                throw $this->_createOutOfRangeException(
                    $this->__('Invalid key'),
                    null,
                    $e,
                    $_key
                );
            }
        }
    }

    /**
     * Assign a single piece of data.
     *
     * @since [*next-version*]
     *
     * @param string|int|float|bool|Stringable $key   The key, for which to assign the data.
     *                                                Unless an integer is given, this will be normalized to string.
     * @param mixed                            $value The value to assign.
     *
     * @throws InvalidArgumentException If key is invalid.
     * @throws RootException            If a problem happens while writing data.
     */
    abstract protected function _setData($key, $value);

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
