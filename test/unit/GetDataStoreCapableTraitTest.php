<?php

namespace Dhii\Data\Object\UnitTest;

use ArrayObject;
use Xpmock\TestCase;
use Dhii\Data\Object\GetDataStoreCapableTrait as TestSubject;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

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
     * @return MockObject
     */
    public function createInstance()
    {
        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                ->getMockForTrait();

        return $mock;
    }

    /**
     * Merges the values of two arrays.
     *
     * The resulting product will be a numeric array where the values of both inputs are present, without duplicates.
     *
     * @since [*next-version*]
     *
     * @param array $destination The base array.
     * @param array $source      The array with more keys.
     *
     * @return array The array which contains unique values
     */
    public function mergeValues($destination, $source)
    {
        return array_keys(array_merge(array_flip($destination), array_flip($source)));
    }

    /**
     * Creates a new store mock.
     *
     * @since [*next-version*]
     *
     * @return ArrayObject|MockObject The new store mock.
     */
    public function createStore($data = [], $methods = [])
    {
        $methods = $this->mergeValues($methods, [
        ]);

        $mock = $this->getMockBuilder('ArrayObject')
            ->setMethods($methods)
            ->getMock($data);

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
     * Tests that the `_getDataStore()` method works correctly when store is empty.
     *
     * @since [*next-version*]
     */
    public function testGetDataStoreCreate()
    {
        $store = $this->createStore();
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
                ->method('_createDataStore')
                ->will($this->returnValue($store));

        $_subject->dataStore = null;
        $result = $_subject->_getDataStore();
        $this->assertSame($store, $result, 'Initial data state was incorrect');
        $this->assertSame($_subject->dataStore, $store, 'Subject did not cache the data store');
    }

    /**
     * Tests that `_getDataStore()` works correctly when store is cached.
     *
     * @since [*next-version*]
     */
    public function testGetDataStoreRetrieve()
    {
        $store = $this->createStore();
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $_subject->dataStore = $store;
        $result = $_subject->_getDataStore();
        $this->assertSame($store, $result, 'Subject did not correctly retrieve cached data store');
    }
}
