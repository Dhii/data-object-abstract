<?php

namespace Dhii\Parser\Tokenizer\FuncTest;

use Xpmock\TestCase;
use Dhii\Data\Object\AbstractDataObject as TestSubject;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class AbstractDataObjectTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Data\Object\AbstractDataObject';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return TestSubject
     */
    public function createInstance()
    {
        $mock = $this->mock(static::TEST_SUBJECT_CLASSNAME)
                ->_createInvalidArgumentException()
                ->_createNotFoundException()
                ->__()
                ->new();

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

        $this->assertInstanceOf(
            static::TEST_SUBJECT_CLASSNAME, $subject, 'Subject is not a valid instance.'
        );
    }

    /**
     * Tests whether getting, setting, checking, and unsetting data work well together on an object.
     *
     * @since [*next-version*]
     */
    public function testSetGetHasUnsetData()
    {
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);
        $key1 = uniqid('key1-');
        $val1 = uniqid('val1-');

        $this->assertEquals([], (array) $_subject->_getData(), 'Initial data state is wrong');
        $this->assertFalse($_subject->_hasData($key1), 'Initial data check result is wrong');

        $_subject->_setData([$key1 => $val1]);
        $this->assertTrue($_subject->_hasData($key1), 'First altered data check result is wrong');
        $this->assertEquals($val1, $_subject->_getData($key1), 'Altered data retrieval result is wrong');

        $_subject->_unsetData([$key1]);
        $this->assertFalse($_subject->_hasData($key1), 'Second altered data check result is wrong');
    }
}
