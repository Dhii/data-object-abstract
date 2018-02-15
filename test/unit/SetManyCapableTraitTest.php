<?php

namespace Dhii\Data\Object\UnitTest;

use ArrayObject;
use InvalidArgumentException;
use OutOfRangeException;
use Exception as RootException;
use Xpmock\TestCase;
use Dhii\Data\Object\SetManyCapableTrait as TestSubject;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;
use Psr\Container\ContainerExceptionInterface;

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
            ->setConstructorArgs($data)
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
        $store = $this->createStore();
        $subject = $this->createInstance(['_normalizeIterable', '_getDataStore', '_containerSetMany']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->with($data)
            ->will($this->returnArgument(0));
        $subject->expects($this->exactly(1))
            ->method('_getDataStore')
            ->with()
            ->will($this->returnValue($store));
        $subject->expects($this->exactly(1))
            ->method('_containerSetMany')
            ->with($store, $data);

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
     * Tests that `_setMany()` fails correctly when one of the keys or the internal data store is invalid.
     *
     * @since [*next-version*]
     */
    public function testSetManyFailureInvalidKeyOrStore()
    {
        $exception = $this->createOutOfRangeException('Invalid data key');
        $data = [uniqid('key') => uniqid('val')];
        $subject = $this->createInstance(['_normalizeIterable', '_containerSetMany']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->will($this->returnValue($data));
        $subject->expects($this->exactly(1))
            ->method('_containerSetMany')
            ->will($this->throwException($exception));

        $this->setExpectedException('OutOfRangeException');
        $_subject->_setMany($data);
    }

    /**
     * Tests that `_setMany()` fails correctly if problem with container.
     *
     * @since [*next-version*]
     */
    public function testSetManyFailureContainer()
    {
        $exception = $this->createContainerException('Container problem');
        $data = [uniqid('key') => uniqid('val')];
        $subject = $this->createInstance(['_normalizeIterable', '_containerSetMany']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->will($this->returnValue($data));
        $subject->expects($this->exactly(1))
            ->method('_containerSetMany')
            ->will($this->throwException($exception));

        $this->setExpectedException('Psr\Container\ContainerExceptionInterface');
        $_subject->_setMany($data);
    }
}
