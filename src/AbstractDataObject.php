<?php

namespace Dhii\Data\Object;

/**
 * Common functionality for data objects.
 *
 * @since [*next-version*]
 */
abstract class AbstractDataObject
{
    /*
     * Adds data store retrieval capability.
     *
     * @since [*next-version*]
     */
    use GetDataStoreCapableTrait;

    /*
     * Adds data retrieval capability.
     *
     * @since [*next-version*]
     */
    use GetDataCapableTrait;

    /*
     * Adds data assignment capability.
     *
     * @since [*next-version*]
     */
    use SetDataCapableTrait;

    /*
     * Adds data checking capability.
     *
     * @since [*next-version*]
     */
    use HasDataCapableTrait;

    /*
     * Adds data removal capability.
     *
     * @since [*next-version*]
     */
    use UnsetDataCapableTrait;
}
