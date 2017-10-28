<?php

namespace Dhii\Data\Object\UnitTest;

use Xpmock\TestCase;
use Dhii\Data\Object\UnsetDataCapableTrait as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class UnsetDataCapableTraitTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Data\Object\UnsetDataCapableTrait';

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
        ]);

        $mock->method('_getDataStore')
                ->will($this->returnValue($data));
        $mock->method('_createInvalidArgumentException')
                ->will($this->returnCallback(function ($message) {
                    return $this->createInvalidArgumentException($message);
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
     * Tests that unsetting data is done correctly.
     *
     * @since [*next-version*]
     */
    public function testUnsetData()
    {
        $key1 = 'name';
        $data = (object) [
            $key1 => uniqid('val1-'),
            'age' => 29,
        ];
        $subject = $this->createInstance($data);
        $_subject = $this->reflect($subject);

        $this->assertObjectHasAttribute($key1, $data, 'Test data initial state is wrong');

        $_subject->_unsetData([$key1]);
        $this->assertObjectNotHasAttribute($key1, $data, 'Test data altered state is wrong');
    }

    /**
     * Tests that unsetting data is done correctly when using a stringable object as key.
     *
     * @since [*next-version*]
     */
    public function testUnsetDataStringable()
    {
        $key = uniqid('key-');
        $value = uniqid('value-');
        $stringable = $this->createStringable($key);
        $data = (object) [
            $key => $value,
        ];
        $subject = $this->createInstance($data);
        $_subject = $this->reflect($subject);

        $_subject->_unsetData([$stringable]);
        $this->assertObjectNotHasAttribute($key, $data, 'Test data altered state is wrong');
    }

    /**
     * Tests that unsetting data fails correctly when given invalid key.
     *
     * @since [*next-version*]
     */
    public function testUnsetDataInvalidKeyFailure()
    {
        $key = new \stdClass();
        $subject = $this->createInstance([]);
        $_subject = $this->reflect($subject);

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_unsetData([$key]);
    }

    /**
     * Tests that unsetting data fails correctly when given invalid key list.
     *
     * @since [*next-version*]
     */
    public function testUnsetDataInvalidKeyListFailure()
    {
        $key = uniqid('key-');
        $subject = $this->createInstance([]);
        $_subject = $this->reflect($subject);

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_unsetData($key);
    }
}
