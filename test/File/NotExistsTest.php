<?php

/**
 * @see       https://github.com/laminas/laminas-validator for the canonical source repository
 * @copyright https://github.com/laminas/laminas-validator/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-validator/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Validator\File;

use Laminas\Validator\File;

/**
 * NotExists testbed
 *
 * @category   Laminas
 * @package    Laminas_Validator_File
 * @subpackage UnitTests
 * @group      Laminas_Validator
 */
class NotExistsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Ensures that the validator follows expected behavior
     *
     * @return void
     */
    public function testBasic()
    {
        $baseDir = __DIR__;
        $valuesExpected = array(
            array($baseDir, 'testsize.mo', true),
            array($baseDir . '/_files', 'testsize.mo', false)
        );

        $files = array(
            'name'        => 'testsize.mo',
            'type'        => 'text',
            'size'        => 200,
            'tmp_name'    => __DIR__ . '/_files/testsize.mo',
            'error'       => 0
        );

        foreach ($valuesExpected as $element) {
            $validator = new File\NotExists($element[0]);
            $this->assertEquals(
                $element[2],
                $validator->isValid($element[1]),
                "Tested with " . var_export($element, 1)
            );
            $this->assertEquals(
                $element[2],
                $validator->isValid($element[1], $files),
                "Tested with " . var_export($element, 1)
                );
        }

        $valuesExpected = array(
            array($baseDir, 'testsize.mo', true, false),
            array($baseDir . '/_files', 'testsize.mo', false, false)
        );

        $files = array(
            'name'        => 'testsize.mo',
            'type'        => 'text',
            'size'        => 200,
            'tmp_name'    => __DIR__ . '/_files/testsize.mo',
            'error'       => 0,
            'destination' => __DIR__ . '/_files'
        );

        foreach ($valuesExpected as $element) {
            $validator = new File\NotExists($element[0]);
            $this->assertEquals(
                $element[2],
                $validator->isValid($element[1]),
                "Tested with " . var_export($element, 1)
            );
            $this->assertEquals(
                $element[3],
                $validator->isValid($element[1], $files),
                "Tested with " . var_export($element, 1)
            );
        }

        $valuesExpected = array(
            array($baseDir, 'testsize.mo', false, false),
            array($baseDir . '/_files', 'testsize.mo', false, false)
        );

        foreach ($valuesExpected as $element) {
            $validator = new File\NotExists();
            $this->assertEquals(
                $element[2],
                $validator->isValid($element[1]),
                "Tested with " . var_export($element, 1)
            );
            $this->assertEquals(
                $element[3],
                $validator->isValid($element[1], $files),
                "Tested with " . var_export($element, 1)
            );
        }
    }

    /**
     * Ensures that getDirectory() returns expected value
     *
     * @return void
     */
    public function testGetDirectory()
    {
        $validator = new File\NotExists('C:/temp');
        $this->assertEquals('C:/temp', $validator->getDirectory());

        $validator = new File\NotExists(array('temp', 'dir', 'jpg'));
        $this->assertEquals('temp,dir,jpg', $validator->getDirectory());

        $validator = new File\NotExists(array('temp', 'dir', 'jpg'));
        $this->assertEquals(array('temp', 'dir', 'jpg'), $validator->getDirectory(true));
    }

    /**
     * Ensures that setDirectory() returns expected value
     *
     * @return void
     */
    public function testSetDirectory()
    {
        $validator = new File\NotExists('temp');
        $validator->setDirectory('gif');
        $this->assertEquals('gif', $validator->getDirectory());
        $this->assertEquals(array('gif'), $validator->getDirectory(true));

        $validator->setDirectory('jpg, temp');
        $this->assertEquals('jpg,temp', $validator->getDirectory());
        $this->assertEquals(array('jpg', 'temp'), $validator->getDirectory(true));

        $validator->setDirectory(array('zip', 'ti'));
        $this->assertEquals('zip,ti', $validator->getDirectory());
        $this->assertEquals(array('zip', 'ti'), $validator->getDirectory(true));
    }

    /**
     * Ensures that addDirectory() returns expected value
     *
     * @return void
     */
    public function testAddDirectory()
    {
        $validator = new File\NotExists('temp');
        $validator->addDirectory('gif');
        $this->assertEquals('temp,gif', $validator->getDirectory());
        $this->assertEquals(array('temp', 'gif'), $validator->getDirectory(true));

        $validator->addDirectory('jpg, to');
        $this->assertEquals('temp,gif,jpg,to', $validator->getDirectory());
        $this->assertEquals(array('temp', 'gif', 'jpg', 'to'), $validator->getDirectory(true));

        $validator->addDirectory(array('zip', 'ti'));
        $this->assertEquals('temp,gif,jpg,to,zip,ti', $validator->getDirectory());
        $this->assertEquals(array('temp', 'gif', 'jpg', 'to', 'zip', 'ti'), $validator->getDirectory(true));

        $validator->addDirectory('');
        $this->assertEquals('temp,gif,jpg,to,zip,ti', $validator->getDirectory());
        $this->assertEquals(array('temp', 'gif', 'jpg', 'to', 'zip', 'ti'), $validator->getDirectory(true));
    }

    /**
     * @group Laminas-11258
     */
    public function testLaminas11258()
    {
        $validator = new File\NotExists();
        $this->assertFalse($validator->isValid(__DIR__ . '/_files/testsize.mo'));
        $this->assertTrue(array_key_exists('fileNotExistsDoesExist', $validator->getMessages()));
        $this->assertContains("'testsize.mo'", current($validator->getMessages()));
    }
}
