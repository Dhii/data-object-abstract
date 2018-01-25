<?php

namespace Dhii\Data\Object\UnitTest;

use Xpmock\TestCase;
use Dhii\Data\Object\HasDataCapableTrait as TestSubject;
use Dhii\Util\String\StringableInterface as Stringable;

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
     * @return object
     */
    public function createInstance($data = null)
    {
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME, array(), '', false, true, true, [
            '_getDataStore',
            '__',
            '_normalizeString',
        ]);

        $mock->method('_getDataStore')
                ->will($this->returnValue($data));
        $mock->method('_createInvalidArgumentException')
                ->will($this->returnCallback(function ($message) {
                    return $this->createInvalidArgumentException($message);
                }));
        $mock->method('_normalizeString')
                ->will($this->returnCallback(function ($string) {
                    return (string) $string;
                }));
        $mock->method('_normalizeKey')
                ->will($this->returnCallback(function ($key) {
                    return is_int($key)
                        ? $key
                        : (string) $key;
                }));

        return $mock;
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
     * Creates a new stringable object.
     *
     * @since [*next-version*]
     *
     * @param string $string The string that the object will represent.
     *
     * @return Stringable The new stringable.
     */
    public function createStringable($string)
    {
        $mock = $this->getMock('Dhii\Util\String\StringableInterface');

        $mock->method('__toString')
                ->will($this->returnValue($string));

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
     * Tests that checking for data existence works correctly.
     *
     * @since [*next-version*]
     */
    public function testHasData()
    {
        $key1 = 'name';
        $data = (object) [
            'age' => 29,
        ];
        $subject = $this->createInstance($data);
        $_subject = $this->reflect($subject);

        $this->assertFalse($_subject->_hasData($key1), 'Test subject initial state is wrong');

        $subject->expects($this->exactly(1))
                ->method('_normalizeKey')
                ->with($this->equalTo($key1));

        $data->{$key1} = uniqid('val1-');
        $this->assertTrue($_subject->_hasData($key1), 'Test subject altered state is wrong');
    }

    /**
     * Tests that checking for data existence works correctly when using a stringable object as key.
     *
     * @since [*next-version*]
     */
    public function testHasDataStringable()
    {
        $key = 'name';
        $stringable = $this->createStringable($key);
        $data = (object) [
            'age' => 29,
        ];
        $subject = $this->createInstance($data);
        $_subject = $this->reflect($subject);

        $this->assertFalse($_subject->_hasData($stringable), 'Test subject initial state is wrong');

        $subject->expects($this->exactly(1))
                ->method('_normalizeKey')
                ->with($this->equalTo($stringable));

        $data->{$key} = uniqid('val1-');
        $this->assertTrue($_subject->_hasData($stringable), 'Test subject altered state is wrong');
    }
}
