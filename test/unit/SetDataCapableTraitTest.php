<?php

namespace Dhii\Data\Object\UnitTest;

use ArrayObject;
use InvalidArgumentException;
use OutOfRangeException;
use Exception as RootException;
use Xpmock\TestCase;
use Dhii\Data\Object\SetDataCapableTrait as TestSubject;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;
use Psr\Container\ContainerExceptionInterface;

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
     * @return MockBuilder The builder for a mock of an object that extends and implements
     *                     the specified class and interfaces.
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

        return $this->getMockBuilder($paddingClassName);
    }

    /**
     * Creates a new Invalid Argument exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The error message.
     *
     * @return MockObject|InvalidArgumentException The new exception.
     */
    public function createInvalidArgumentException($message = '')
    {
        $mock = $this->getMockBuilder('InvalidArgumentException')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new Invalid Argument exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The error message.
     *
     * @return MockObject|OutOfRangeException The new exception.
     */
    public function createOutOfRangeException($message = '')
    {
        $mock = $this->getMockBuilder('OutOfRangeException')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new Invalid Argument exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The error message.
     *
     * @return MockObject|RootException|ContainerExceptionInterface The new exception.
     */
    public function createContainerException($message = '')
    {
        $mock = $this->mockClassAndInterfaces('Exception', ['Psr\Container\ContainerExceptionInterface'])
            ->setConstructorArgs([$message])
            ->getMock();

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
     * Tests that `_setData()` works as expected.
     *
     * @since [*next-version*]
     */
    public function testSetData()
    {
        $key = uniqid('key');
        $val = uniqid('val');
        $store = $this->createStore();
        $subject = $this->createInstance(['_getDataStore', '_containerSet']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_getDataStore')
            ->will($this->returnValue($store));
        $subject->expects($this->exactly(1))
            ->method('_containerSet')
            ->with($store, $key, $val);

        $_subject->_setData($key, $val);
    }

    /**
     * Tests that `_setData()` fails as expected if the inner store is invalid.
     *
     * @since [*next-version*]
     */
    public function testSetDataFailureInvalidStore()
    {
        $key = uniqid('key');
        $val = uniqid('val');
        $store = $this->createStore();
        $innerException = $this->createInvalidArgumentException('Invalid store');
        $exception = $this->createOutOfRangeException('Invalid store');
        $subject = $this->createInstance(['_getDataStore', '_containerSet', '_createOutOfRangeException']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_getDataStore')
            ->will($this->returnValue($store));
        $subject->expects($this->exactly(1))
            ->method('_containerSet')
            ->with($store, $key, $val)
            ->will($this->throwException($innerException));
        $subject->expects($this->exactly(1))
            ->method('_createOutOfRangeException')
            ->with(
                $this->isType('string'),
                null,
                $innerException,
                $store
            )
            ->will($this->returnValue($exception));

        $this->setExpectedException('OutOfRangeException');
        $_subject->_setData($key, $val);
    }

    /**
     * Tests that `_setData()` fails as expected if the key is invalid.
     *
     * @since [*next-version*]
     */
    public function testSetDataFailureInvalidKey()
    {
        $key = uniqid('key');
        $val = uniqid('val');
        $store = $this->createStore();
        $innerException = $this->createOutOfRangeException('Invalid key');
        $exception = $this->createInvalidArgumentException('Invalid key');
        $subject = $this->createInstance(['_getDataStore', '_containerSet', '_createInvalidArgumentException']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_getDataStore')
            ->will($this->returnValue($store));
        $subject->expects($this->exactly(1))
            ->method('_containerSet')
            ->with($store, $key, $val)
            ->will($this->throwException($innerException));
        $subject->expects($this->exactly(1))
            ->method('_createInvalidArgumentException')
            ->with(
                $this->isType('string'),
                null,
                $innerException,
                $store
            )
            ->will($this->returnValue($exception));

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_setData($key, $val);
    }

    /**
     * Tests that `_setData()` fails as expected if a problem occurs with the container.
     *
     * @since [*next-version*]
     */
    public function testSetDataFailureContainer()
    {
        $key = uniqid('key');
        $val = uniqid('val');
        $store = $this->createStore();
        $exception = $this->createContainerException('Container problem');
        $subject = $this->createInstance(['_getDataStore', '_containerSet']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_getDataStore')
            ->will($this->returnValue($store));
        $subject->expects($this->exactly(1))
            ->method('_containerSet')
            ->with($store, $key, $val)
            ->will($this->throwException($exception));

        $this->setExpectedException('Psr\Container\ContainerExceptionInterface');
        $_subject->_setData($key, $val);
    }
}
