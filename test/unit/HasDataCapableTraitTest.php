<?php

namespace Dhii\Data\Object\UnitTest;

use ArrayObject;
use InvalidArgumentException;
use Xpmock\TestCase;
use Dhii\Data\Object\HasDataCapableTrait as TestSubject;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class HasDataCapableTraitTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Data\Object\HasDataCapableTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return MockObject
     */
    public function createInstance($methods = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
            '__',
        ]);

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
            ->setMethods($methods)
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
     * Creates a new Invalid Argument exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The error message.
     *
     * @return InvalidArgumentException The new exception.
     */
    public function createInvalidArgumentException($message = '')
    {
        $mock = $this->getMock('InvalidArgumentException');

        $mock->method('getMessage')
                ->will($this->returnValue($message));

        return $mock;
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
     * Tests that checking for data existence works correctly when data exists.
     *
     * @since [*next-version*]
     */
    public function testHasDataTrue()
    {
        $key = uniqid('key');
        $isExists = true;
        $store = $this->createStore();
        $subject = $this->createInstance(['_getDataStore', '_containerHas']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
                ->method('_getDataStore')
                ->will($this->returnValue($store));

        $subject->expects($this->exactly(1))
                ->method('_containerHas')
                ->with(
                    $store,
                    $key
                )
                ->will($this->returnValue($isExists));

        $this->assertEquals($isExists, $_subject->_hasData($key), 'Test subject did not determine that it has the specified data key');
    }

    /**
     * Tests that checking for data existence works correctly when data doesn't exist.
     *
     * @since [*next-version*]
     */
    public function testHasDataFalse()
    {
        $key = uniqid('key');
        $isExists = false;
        $store = $this->createStore();
        $subject = $this->createInstance(['_getDataStore', '_containerHas']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_getDataStore')
            ->will($this->returnValue($store));

        $subject->expects($this->exactly(1))
            ->method('_containerHas')
            ->with(
                $store,
                $key
            )
            ->will($this->returnValue($isExists));

        $this->assertEquals($isExists, $_subject->_hasData($key), 'Test subject did not determine that it does not have the specified data key');
    }
}
