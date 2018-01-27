<?php

namespace Dhii\Data\Object\UnitTest;

use Dhii\Data\Object\NormalizeKeyCapableTrait as TestSubject;
use Xpmock\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Dhii\Util\String\StringableInterface as Stringable;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class NormalizeKeyCapableTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Data\Object\NormalizeKeyCapableTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods The methods to mock.
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
     * Creates a new exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return RootException The new exception.
     */
    public function createException($message = '')
    {
        $mock = $this->getMockBuilder('Exception')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Create a stringable object that wraps a given string.
     *
     * @param string $string The string that the stringable will wrap.
     *
     * @return Stringable The new stringable.
     */
    public function createStringable($string = '')
    {
        $mock = $this->getMockBuilder('Dhii\Util\String\StringableInterface')
                ->setMethods(['__toString'])
                ->getMock();

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

        $this->assertInternalType(
            'object',
            $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests that the `_normalizeKey()` method works as expected when given a stringable.
     *
     * @since [*next-version*]
     */
    public function testNormalizeKeyStringable()
    {
        $key = uniqid('key');
        $stringable = $this->createStringable($key);
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
                ->method('_normalizeString')
                ->with($stringable)
                ->will($this->returnValue($key));

        $result = $_subject->_normalizeKey($stringable);
        $this->assertEquals($key, $result, 'Normalization of a stringable did not produce expected result');
    }

    /**
     * Tests that the `_normalizeKey()` method works as expected when given an integer.
     *
     * @since [*next-version*]
     */
    public function testNormalizeKeyInt()
    {
        $key = rand(1, 99);
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $result = $_subject->_normalizeKey($key);
        $this->assertEquals($key, $result, 'Normalization of an integer did not produce expected result');
    }
}
