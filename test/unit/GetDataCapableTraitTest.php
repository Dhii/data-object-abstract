<?php

namespace Dhii\Data\Object\UnitTest;

use Xpmock\TestCase;
use Dhii\Data\Object\GetDataCapableTrait as TestSubject;
use Psr\Container\NotFoundExceptionInterfaces;
use InvalidArgumentException;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class GetDataCapableTraitTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Data\Object\GetDataCapableTrait';

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
        $mock->method('_createNotFoundException')
                ->will($this->returnCallback(function ($message) {
                    return $this->createNotFoundException($message);
                }));
        $mock->method('_createInvalidArgumentException')
                ->will($this->returnCallback(function ($message) {
                    return $this->createInvalidArgumentException($message);
                }));
        $mock->method('_normalizeString')
                ->will($this->returnCallback(function ($string) {
                    return (string) $string;
                }));

        return $mock;
    }

    /**
     * Creates a mock that both extends a class and implements interfaces.
     *
     * This is particularly useful for cases where the mock is based on an
     * internal class, such as in the case with exceptions. Helps to avoid
     * writing hard-coded stubs.
     *
     * @since [*next-version*]
     *
     * @param string $className      Name of the class for the mock to extend.
     * @param string $interfaceNames Names of the interfaces for the mock to implement.
     *
     * @return object The object that extends and implements the specified class and interfaces.
     */
    public function mockClassAndInterfaces($className, $interfaceNames = [])
    {
        $paddingClassName = uniqid($className);
        $definition = vsprintf('abstract class %1$s extends %2$s implements %3$s {}', [
            $paddingClassName,
            $className,
            implode(', ', $interfaceNames),
        ]);
        eval($definition);

        return $this->getMockForAbstractClass($paddingClassName);
    }

    /**
     * Creates a new Not Found exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The error message.
     *
     * @return NotFoundExceptionInterfaces The new exception.
     */
    public function createNotFoundException($message = '')
    {
        $mock = $this->mockClassAndInterfaces('Exception', ['Psr\Container\NotFoundExceptionInterface']);
        $mock->method('getMessage')
                ->will($this->returnValue($message));

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
     * Tests that the reference to a data store is returned correctly.
     *
     * @since [*next-version*]
     */
    public function testGetData()
    {
        $key1 = 'name';
        $val2 = uniqid('val2-');
        $data = (object) [
            $key1 => 'Anton',
            'age' => 29,
        ];
        $subject = $this->createInstance($data);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
                ->method('_normalizeString')
                ->with($this->equalTo($key1));

        $this->assertEquals((array) $data, $_subject->_getData(), 'The state of the whole data map is wrong', 0.0, 10, true);
        $data->{$key1} = $val2;

        $this->assertEquals($val2, $_subject->_getData($key1), 'Data member could not be correctly retrieved');
    }

    /**
     * Tests that data retrieval works correctly when using a stringable object key.
     *
     * @since [*next-version*]
     */
    public function testGetDataStringable()
    {
        $key = uniqid('key-');
        $value = uniqid('value-');
        $stringable = $this->createStringable($key);
        $data = (object) [
            $key => $value,
        ];
        $subject = $this->createInstance($data);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
                ->method('_normalizeString')
                ->with($this->equalTo($stringable));

        $this->assertEquals($value, $_subject->_getData($stringable), 'Data member could not be correctly retrieved');
    }

    /**
     * Tests that accessing a non-existing key fails correctly.
     *
     * @since [*next-version*]
     */
    public function testGetDataNotFoundFailure()
    {
        $key = uniqid('key-');
        $value = uniqid('value-');
        $key2 = uniqid('key2-');
        $data = (object) [
            $key => $value,
        ];
        $subject = $this->createInstance($data);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
                ->method('_normalizeString')
                ->with($this->equalTo($key2));

        $this->setExpectedException('Psr\Container\NotFoundExceptionInterface');
        $_subject->_getData($key2);
    }
}
