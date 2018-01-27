<?php

namespace Dhii\Data\Object\UnitTest;

use InvalidArgumentException;
use OutOfRangeException;
use Xpmock\TestCase;
use Dhii\Data\Object\SetManyCapableTrait as TestSubject;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class SetManyCapableTraitTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Data\Object\SetManyCapableTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return MockObject The new instance.
     */
    public function createInstance($methods = [])
    {
        $methods = $this->mergeValues($methods, [
            '__',
        ]);

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
            ->setMethods($methods)
            ->getMockForTrait();

        $mock->method('__')
                ->will($this->returnArgument(0));

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
     * Creates a new Out of Range exception mock.
     *
     * @since [*next-version*]
     *
     * @return OutOfRangeException|MockObject The new Out of Range exception mock.
     */
    public function createOutOfRangeException($message = '')
    {
        $mock = $this->getMockBuilder('OutOfRangeException')
            ->setConstructorArgs([$message])
            ->getMock();

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
     * Tests that `_setMany()` works correctly.
     *
     * @since [*next-version*]
     */
    public function testSetMany()
    {
        $data = [
            uniqid('key') => uniqid('val'),
            uniqid('key') => uniqid('val'),
            uniqid('key') => uniqid('val'),
        ];
        $subject = $this->createInstance(['_normalizeIterable', '_setData']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
                ->method('_normalizeIterable')
                ->with($data)
                ->will($this->returnValue($data));
        $methodMock = $subject->expects($this->exactly(count($data)))
                ->method('_setData');
        $methodArgs = $data;
        array_walk($methodArgs, function (&$val, $key) {
            $val = [$key, $val];
        });
        call_user_func_array([$methodMock, 'withConsecutive'], $methodArgs);

        $_subject->_setMany($data);
    }

    /**
     * Tests that `_setMany()` fails correctly when given an invalid data map.
     *
     * @since [*next-version*]
     */
    public function testSetManyFailureInvalidMap()
    {
        $exception = $this->createInvalidArgumentException('Data map is invalid');
        $data = [uniqid('key') => uniqid('val')];
        $subject = $this->createInstance(['_normalizeIterable']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->will($this->throwException($exception));

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_setMany($data);
    }

    /**
     * Tests that `_setMany()` fails correctly when one of the keys is invalid.
     *
     * @since [*next-version*]
     */
    public function testSetManyFailureInvalidKey()
    {
        $innerException = $this->createInvalidArgumentException('Data key is invalid');
        $exception = $this->createOutOfRangeException('Invalid data key');
        $data = [uniqid('key') => uniqid('val')];
        $subject = $this->createInstance(['_normalizeIterable', '_setData']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
                ->method('_normalizeIterable')
                ->will($this->returnValue($data));
        $subject->expects($this->exactly(1))
                ->method('_setData')
                ->will($this->throwException($innerException));
        $subject->expects($this->exactly(1))
                ->method('_createOutOfRangeException')
                ->with()
                ->will($this->returnValue($exception));

        $this->setExpectedException('OutOfRangeException');
        $_subject->_setMany($data);
    }
}
