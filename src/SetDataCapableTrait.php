<?php

namespace Dhii\Data\Object;

use Dhii\Util\String\StringableInterface as Stringable;
use Traversable;
use Exception as RootException;
use InvalidArgumentException;

/**
 * Functionality for data assignment.
 *
 * @since [*next-version*]
 */
trait SetDataCapableTrait
{
    /**
     * Assign data.
     *
     * @since [*next-version*]
     *
     * @param iterable $data The data to set. Existing keys will be overwritten.
     *                       The rest of the data remains unaltered.
     */
    protected function _setData($data)
    {
        if (!is_array($data) && !($data instanceof Traversable)) {
            throw $this->_createInvalidArgumentException($this->__('Data must be iterable'), null, null, $data);
        }

        $store = $this->_getDataStore();
        foreach ($data as $_key => $_value) {
            $store->{(string) $_key} = $_value;
        }
    }

    /**
     * Retrieves the data store obeject.
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
