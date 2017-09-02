<?php

namespace Dhii\Data\Object\UnitTest;

use Xpmock\TestCase;
use Dhii\Data\Object\UnsetDataCapableTrait as TestSubject;

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
    public function createInstance($data = null)
    {
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME, array(), '', false, true, true, [
            '_getDataStore',
            '_createInvalidArgumentException',
            '__',
        ]);

        $mock->method('_getDataStore')
                ->will($this->returnValue($data));

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
    public function testHasData()
    {
        $key1 = 'name';
        $data = (object) [
            $key1 => uniqid('val1-'),
            'age' => 29,
        ];
        $subject = $this->createInstance($data);
        $_subject = $this->reflect($subject);

        $this->assertObjectHasAttribute($key1, $data, 'Test data initial state is wrong');

        $_subject->_unsetData([$key1]);
        $this->assertObjectNotHasAttribute($key1, $data, 'Test data altered state is wrong');
    }
}
