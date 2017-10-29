<?php

namespace Dhii\Data\Object\UnitTest;

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
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME);

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
        $data = new \stdClass();
        $subject = $this->createInstance($data);
        $_subject = $this->reflect($subject);
        $key1 = uniqid('key1-');
        $val1 = uniqid('val1-');

        $result = $_subject->_getDataStore();
        $this->assertEquals($data, $result, 'Initial data state was not an empty array');

        $_subject->dataStore = $data;
        $data->{$key1} = $val1;
        $newData = $_subject->_getDataStore();
        $this->assertObjectHasAttribute($key1, $newData, 'Internal storage does not reflect new state');
        $this->assertEquals($val1, $newData->{$key1}, 'Internal storage does not reflect new state');
    }
}
