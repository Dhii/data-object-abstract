<?php

namespace Dhii\Data\Object\UnitTest;

use ArrayObject;
use Xpmock\TestCase;
use Dhii\Data\Object\GetDataStoreCapableTrait as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class GetDataStoreCapableTraitTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Data\Object\GetDataStoreCapableTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return object
     */
    public function createInstance()
    {
        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                ->getMockForTrait();

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType('object', $subject, 'An instance of the test subject could not be created');
    }

    /**
     * Tests that the reference to a data store is returned correctly.
     *
     * @since [*next-version*]
     */
    public function testGetDataStore()
    {
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);
        $key1 = uniqid('key1-');
        $val1 = uniqid('val1-');

        $store = $_subject->_getDataStore();
        $this->assertInstanceOf('ArrayObject', $store, 'Initial data state was incorrect');

        $store->offsetSet($key1, $val1);
        $store = $_subject->_getDataStore();
        $this->assertTrue($store->offsetExists($key1), 'Internal storage does not reflect new state');
        $this->assertEquals($val1, $store->offsetGet($key1), 'Internal storage does not reflect new state');
    }
}
