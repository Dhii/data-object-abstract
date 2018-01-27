<?php

namespace Dhii\Data\Object\UnitTest;

use Dhii\Data\Object\CreateDataStoreCapableTrait as TestSubject;
use InvalidArgumentException;
use Xpmock\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class CreateDataStoreCapableTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Data\Object\CreateDataStoreCapableTrait';

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
     * Tests that `_createDataObject()` works as expected.
     *
     * @since [*next-version*]
     */
    public function testCreateDataStore()
    {
        $data = [uniqid('key') => uniqid('val')];
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $result = $_subject->_createDataStore($data);
        $this->assertInstanceOf('ArrayObject', $result, 'Subject could not create a valid instance of the data store.');

        $resultData = iterator_to_array($result, true);
        $this->assertArraySubset($data, $resultData, 'Created data store has wrong data');
    }

    /**
     * Tests that `_createDataObject()` works as expected when using default data.
     *
     * @since [*next-version*]
     */
    public function testCreateDataStoreDefaultData()
    {
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $result = $_subject->_createDataStore();
        $this->assertCount(0, $result, 'Created data store has wrong data');
    }

    /**
     * Tests that `_createDataObject()` fails as expected when using invalid data.
     *
     * @since [*next-version*]
     */
    public function testCreateDataStoreFailureInvalidData()
    {
        $data = uniqid('data');
        $exception = $this->createInvalidArgumentException('Could not create data store');
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
                ->method('_createInvalidArgumentException')
                ->with(
                  $this->isType('string'),
                  null,
                  $this->isInstanceOf('Exception'),
                  $data
                )
                ->will($this->returnValue($exception));

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_createDataStore($data);
    }
}
