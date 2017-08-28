<?php

namespace Dhii\Data\Object\FuncTest;

use Xpmock\TestCase;
use Dhii\Data\Object\GetDataCapableTrait as TestSubject;

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
    public function createInstance(&$data = [])
    {
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME, array(), '', false, true, true, [
            '_getDataStore',
            '_createNotFoundException',
            '_createInvalidArgumentException',
            '__',
        ]);

        $mock->method('_getDataStore')
                ->will($this->returnCallback(function &() use (&$data) {
                    return $data;
                }));

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
        $data = [
            'name' => 'Anton',
            'age' => 29,
        ];
        $subject = $this->createInstance($data);
        $_subject = $this->reflect($subject);

        $this->assertEquals($data, $_subject->_getData(), 'The state of the whole data map is wrong', 0.0, 10, true);
        $this->assertEquals('Anton', $_subject->_getData('name'), 'Data member could not be correctly retrieved');
    }
}
