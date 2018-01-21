<?php

namespace Dhii\Data\Object\UnitTest;

use Psr\Container\NotFoundExceptionInterface;
use Xpmock\TestCase;
use Dhii\Data\Object\UnsetDataCapableTrait as TestSubject;
use Dhii\Util\String\StringableInterface as Stringable;
use InvalidArgumentException;

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
     * Creates a new Not Found exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The error message.
     *
     * @return NotFoundExceptionInterface The new exception.
     */
    public function createNotFoundException($message = '')
    {
        $mock = $this->mockClassAndInterfaces('Exception', ['Psr\Container\NotFoundExceptionInterface']);
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
        $subject = $this->createInstance(['_getDataStore']);
        $_subject = $this->reflect($subject);

        $this->assertObjectHasAttribute($key1, $data, 'Test data initial state is wrong');

        $subject->method('_getDataStore')
                ->will($this->returnValue($data));
        $subject->expects($this->exactly(1))
                ->method('_normalizeString')
                ->with($this->equalTo($key1));

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
        $subject = $this->createInstance(['_getDataStore']);
        $_subject = $this->reflect($subject);

        $subject->method('_getDataStore')
                ->will($this->returnValue($data));
        $subject->expects($this->exactly(1))
                ->method('_normalizeString')
                ->with($this->equalTo($stringable));

        $_subject->_unsetData([$stringable]);
        $this->assertObjectNotHasAttribute($key, $data, 'Test data altered state is wrong');
    }

    /**
     * Tests that unsetting data fails correctly when given invalid key list.
     *
     * @since [*next-version*]
     */
    public function testUnsetDataInvalidKeyListFailure()
    {
        $key = uniqid('key-');
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_unsetData($key);
    }

    /**
     * Tests that the subject fails correctly when attempting to unset non-existing key.
     *
     * @since [*next-version*]
     */
    public function testUnsetDataFailureNotFound()
    {
        $key = uniqid('key');
        $subject = $this->createInstance(['_throwNotFoundException']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
                ->method('_throwNotFoundException')
                ->with(
                    $this->isType('string'),
                    $this->isNull(),
                    $this->isNull(),
                    $this->isNull(),
                    $key
                )
                ->will($this->returnCallback(function ($message) {
                    throw $this->createNotFoundException($message);
                }));

        $this->setExpectedException('Psr\Container\NotFoundExceptionInterface');
        $_subject->_unsetData([$key]);
    }
}
