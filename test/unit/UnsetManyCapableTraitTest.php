<?php

namespace Dhii\Data\Object\UnitTest;

use ArrayObject;
use OutOfRangeException;
use Exception as RootException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Xpmock\TestCase;
use Dhii\Data\Object\UnsetManyCapableTrait as TestSubject;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;
use InvalidArgumentException;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class UnsetManyCapableTraitTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Data\Object\UnsetManyCapableTrait';

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
            '_normalizeString',
        ]);

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
                ->setMethods($methods)
                ->getMockForTrait();

        $mock->method('_createInvalidArgumentException')
                ->will($this->returnCallback(function ($message) {
                    return $this->createInvalidArgumentException($message);
                }));
        $mock->method('_normalizeString')
                ->will($this->returnCallback(function ($string) {
                    return (string) $string;
                }));
        $mock->method('_normalizeIterable')
                ->will($this->returnCallback(function ($iterable) {
                    return $iterable;
                }));
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
     * @param string   $className      Name of the class for the mock to extend.
     * @param string[] $interfaceNames Names of the interfaces for the mock to implement.
     *
     * @return MockBuilder The object that extends and implements the specified class and interfaces.
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
        $mock = $this->getMock('InvalidArgumentException');

        $mock->method('getMessage')
            ->will($this->returnValue($message));

        return $mock;
    }

    /**
     * Creates a new Container exception.
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
     * Creates a new Not Found exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The error message.
     *
     * @return MockObject|RootException|NotFoundExceptionInterface The new exception.
     */
    public function createNotFoundException($message = '')
    {
        $mock = $this->mockClassAndInterfaces('Exception', ['Psr\Container\NotFoundExceptionInterface'])
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new store mock.
     *
     * @since [*next-version*]
     *
     * @param array $data|stdObject The data for the store.
     * @param array $methods        The methods to mock;
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
     * Creates a new Out of Bounds exception mock.
     *
     * @since [*next-version*]
     *
     * @return OutOfRangeException|MockObject The new Out of Bounds exception mock.
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
     * Tests that `_unsetMany()` works as expected.
     *
     * @since [*next-version*]
     */
    public function testUnsetMany()
    {
        $keys = [
            uniqid('key'),
            uniqid('key'),
            uniqid('key'),
        ];
        $store = $this->createStore();
        $subject = $this->createInstance(['_normalizeIterable', '_containerUnsetMany']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->with($keys)
            ->will($this->returnValue($keys));
        $subject->expects($this->exactly(1))
            ->method('_getDataStore')
            ->with()
            ->will($this->returnValue($store));
        $subject->expects($this->exactly(1))
            ->method('_containerUnsetMany')
            ->with($store, $keys);

        $_subject->_unsetMany($keys);
    }

    /**
     * Tests that `_unsetMany()` fails as expected when using an invalid list.
     *
     * @since [*next-version*]
     */
    public function testUnsetDataInvalidKeyListFailure()
    {
        $keys = uniqid('keys');
        $exception = $this->createInvalidArgumentException('Invalid list');
        $subject = $this->createInstance(['_normalizeIterable']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
                ->method('_normalizeIterable')
                ->with($keys)
                ->will($this->throwException($exception));

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_unsetMany($keys);
    }

    /**
     * Tests that `_unsetMany()` fails as expected when trying to unset an invalid key.
     *
     * @since [*next-version*]
     */
    public function testUnsetDataFailureInvalidKey()
    {
        $key = uniqid('key');
        $keys = [$key];
        $store = $this->createStore();
        $exception = $this->createOutOfRangeException('One of the keys is invalid');
        $subject = $this->createInstance(['_normalizeIterable', '_containerUnsetMany']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->with($keys)
            ->will($this->returnArgument(0));
        $subject->expects($this->exactly(1))
            ->method('_getDataStore')
            ->with()
            ->will($this->returnValue($store));
        $subject->expects($this->exactly(1))
            ->method('_containerUnsetMany')
            ->with($store, $keys)
            ->will($this->throwException($exception));

        $this->setExpectedException('OutOfRangeException');
        $_subject->_unsetMany($keys);
    }

    /**
     * Tests that `_unsetMany()` fails as expected when trying to unset an invalid key.
     *
     * @since [*next-version*]
     */
    public function testUnsetDataFailureInvalidStore()
    {
        $key = uniqid('key');
        $keys = [$key];
        $store = uniqid('store');
        $innerException = $this->createInvalidArgumentException('Invalid containerInvalid store');
        $exception = $this->createOutOfRangeException('Invalid store');
        $subject = $this->createInstance(['_normalizeIterable', '_createOutOfRangeException']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->with($keys)
            ->will($this->returnArgument(0));
        $subject->expects($this->exactly(1))
            ->method('_getDataStore')
            ->with()
            ->will($this->returnValue($store));
        $subject->expects($this->exactly(1))
            ->method('_containerUnsetMany')
            ->with($store, $keys)
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
        $_subject->_unsetMany($keys);
    }

    /**
     * Tests that `_unsetMany()` fails as expected when trying to unset a non-existing key.
     *
     * @since [*next-version*]
     */
    public function testUnsetDataFailureNotFound()
    {
        $keys = [uniqid('key')];
        $store = $this->createStore();
        $exception = $this->createNotFoundException('Key not found');
        $subject = $this->createInstance(['_unsetData']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_normalizeIterable')
            ->with($keys)
            ->will($this->returnArgument(0));
        $subject->expects($this->exactly(1))
            ->method('_getDataStore')
            ->with()
            ->will($this->returnValue($store));
        $subject->expects($this->exactly(1))
                ->method('_containerUnsetMany')
                ->with($store, $keys)
                ->will($this->throwException($exception));

        $this->setExpectedException('Psr\Container\NotFoundExceptionInterface');
        $_subject->_unsetMany($keys);
    }
}
