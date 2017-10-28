<?php

namespace Dhii\Data\Object\UnitTest;

use Xpmock\TestCase;
use Dhii\Data\Object\SetDataCapableTrait as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class SetDataCapableTraitTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Data\Object\SetDataCapableTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return object
     */
    public function createInstance(&$data = [])
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
    public function testSetData()
    {
        $key1 = 'name';
        $val1 = uniqid('val1-');
        $val2 = uniqid('val2-');
        $data = (object) [
            $key1 => $val1,
            'age' => 29,
        ];
        $subject = $this->createInstance($data);
        $_subject = $this->reflect($subject);

        $this->assertEquals($val1, $data->{$key1}, 'The initial state of the data member is wrong');
        $_subject->_setData([$key1 => $val2]);
        $this->assertEquals($val2, $data->{$key1}, 'The new state of the data member is wrong');
    }

    /**
     * Tests that using an invalid data map fails correctly.
     *
     * @since [*next-version*]
     */
    public function testSetDataInvalidMapFailure()
    {
        $data = new \stdClass();
        $subject = $this->createInstance($data);
        $_subject = $this->reflect($subject);

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_setData($data);
    }
}
