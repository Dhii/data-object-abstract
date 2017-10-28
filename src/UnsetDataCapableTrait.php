<?php

namespace Dhii\Data\Object;

use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;
use InvalidArgumentException;

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
            if (!is_string($_key) && !($_key instanceof Stringable)) {
                throw $this->_createInvalidArgumentException($this->__('Data key #%1$s must be stringable', $_idx), null, null, $keys);
            }

            unset($store->{(string) $_key});
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
     * Creates a new not found exception.
     *
     * @param string|Stringable|null $message  The message for the exception, if any.
     * @param string|Stringable|null $dataKey  The data key, if any.
     * @param RootException|null     $previous The inner exception, if any.
     *
     * @since [*next-version*]
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
