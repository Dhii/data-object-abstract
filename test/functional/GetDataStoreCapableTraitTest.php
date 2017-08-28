<?php

namespace Dhii\Data\Object\FuncTest;

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
        $subject = $this->createInstance();
        $_subject = new \ReflectionClass($subject);
        $_method = $_subject->getMethod('_getDataStore');
        $_method->setAccessible(true);
        $_method = $_method->getClosure($subject);
        $key1 = uniqid('key1-');
        $val1 = uniqid('val1-');

        $data = &$_method();

        $this->assertEquals([], $data, 'Initial data state was not an empty array');
        $data[$key1] = $val1;

        $newData = $_method();
        $this->assertArrayHasKey($key1, $newData, 'Internal storage does not reflect new state');
        $this->assertEquals($val1, $newData[$key1], 'Internal storage does not reflect new state');
    }
}
