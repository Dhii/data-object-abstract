<?php

namespace Dhii\Data\Object;

use ArrayAccess;
use InvalidArgumentException;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;

/**
 * Functionality for assigning a data store.
 *
 * @since [*next-version*]
 */
trait SetDataStoreCapableTrait
{
    /**
     * The data store.
     *
     * @since [*next-version*]
     *
     * @var ArrayAccess|null
     */
    protected $dataStore;

    /**
     * Assigns a data store to this instance.
     *
     * @since [*next-version*]
     *
     * @param ArrayAccess|null $dataStore The data store to set.
     */
    protected function _setDataStore($dataStore)
    {
        if (!($dataStore instanceof ArrayAccess) && !is_null($dataStore)) {
            throw $this->_createInvalidArgumentException(
                $this->__('Invalid data store'),
                null,
                null,
                $dataStore
            );
        }

        $this->dataStore = $dataStore;
    }

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
}
