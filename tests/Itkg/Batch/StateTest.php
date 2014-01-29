<?php

namespace Itkg\Batch;


/**
 * Classe pour les test phpunit pour la classe State
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 * @author Benoit de JACOBET <benoit.dejacobet@businessdecision.com>
 * @author Clément GUINET <clement.guinet@businessdecision.com>
 *
 * @package \Itkg
 * 
 */
class StateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Writer
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new State();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Itkg\Batch\State::__construct
     */
    public function test__construct()
    {
        $this->assertEquals(0, $this->object->getStatus());
        $this->assertInstanceOf('DateTime', $this->object->getDate());
    }
     
    /**
     * @covers Itkg\Batch\State::getDuration
     */
    public function testGetDuration()
    {
        $this->object->setStart(10000);
        $this->object->setEnd(25000);
        $this->assertEquals(15000, $this->object->getDuration());
    }
        /**
     * @covers Itkg\Batch\State::setException
     */
    public function testSetException()
    {
        $e = new \Exception();
        $this->object->setException($e);
        $this->assertEquals($e, $this->object->getException());
        $this->assertEquals(1, $this->object->getStatus());
    }
}
