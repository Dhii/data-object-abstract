<?php

namespace Dhii\Data\Object\UnitTest;

use ArrayObject;
use Xpmock\TestCase;
use Dhii\Data\Object\GetDataCapableTrait as TestSubject;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Psr\Container\NotFoundExceptionInterface;
use InvalidArgumentException;
use Exception as RootException;
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
     * @return MockObject
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
            ->will($this->returnCallback(function ($string) {
                return $string;
            }));

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
     * @return MockObject|NotFoundExceptionInterface|RootException The new exception.
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
                ->getMock();

        return $mock;
    }

    /**
     * Tests that data gets retrieved correctly.
     *
     * @since [*next-version*]
     */
    public function testGetData()
    {
        $key = uniqid('name');
        $val = uniqid('val2-');
        $data = [
            $key => $val,
        ];
        $store = $this->createStore($data);
        $subject = $this->createInstance(['_containerGet']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
                ->method('_getDataStore')
                ->will($this->returnValue($store));
        $subject->expects($this->exactly(1))
                ->method('_containerGet')
                ->with(
                    $this->equalTo($store),
                    $this->equalTo($key)
                )
                ->will($this->returnValue($val));

        $this->assertEquals($val, $_subject->_getData($key), 'Data member could not be correctly retrieved');
    }

    /**
     * Tests that accessing a non-existing key fails correctly.
     *
     * @since [*next-version*]
     */
    public function testGetDataNotFoundFailure()
    {
        $key = uniqid('key');
        $store = $this->createStore();
        $exception = $this->createNotFoundException('Key not found');
        $subject = $this->createInstance(['_getDataStore', '_containerGet']);
        $_subject = $this->reflect($subject);

        $subject->method('_getDataStore')
                ->will($this->returnValue($store));
        $subject->expects($this->exactly(1))
                ->method('_containerGet')
                ->with(
                    $this->equalTo($store),
                    $this->equalTo($key)
                )
                ->will($this->throwException($exception));

        $this->setExpectedException('Psr\Container\NotFoundExceptionInterface');
        $_subject->_getData($key);
    }
}
