<?php

namespace Dhii\Data\Object;

use ArrayObject;
use InvalidArgumentException;
use stdClass;
use ArrayAccess;
use Dhii\Util\String\StringableInterface as Stringable;
use Exception as RootException;

/**
 * Functionality for retrieval of the data store.
 *
 * @since [*next-version*]
 */
trait DataStoreAwareTrait
{
    /**
     * The data store.
     *
     * @since [*next-version*]
     *
     * @var ArrayObject|null
     */
    protected $dataStore;

    /**
     * Retrieves a pointer to the data store.
     *
     * @since [*next-version*]
     *
     * @return ArrayObject The data store.
     */
    protected function _getDataStore()
    {
        return $this->dataStore === null
            ? $this->dataStore = $this->_createDataStore()
            : $this->dataStore;
    }

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

    /**
     * Creates a new data store.
     *
     * @since [*next-version*]
     *
     * @param stdClass|array|null $data The data for the store, if any.
     *
     * @throws InvalidArgumentException If the type of data for the store is invalid.
     *
     * @return ArrayObject The new data store.
     */
    abstract protected function _createDataStore($data = null);
}
