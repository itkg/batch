<?php

namespace Itkg;

use Itkg\Log\Handler\EchoHandler;

/**
 * Classe pour les test phpunit pour la classe Batch
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 * @author Benoit de JACOBET <benoit.dejacobet@businessdecision.com>
 * @author Clément GUINET <clement.guinet@businessdecision.com>
 *
 * @package \Itkg
 * 
 */
class BatchTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Itkg\Mock\Batch
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
      \Itkg\Log::$config['DEFAULT_HANDLER'] = new EchoHandler();
      $this->object = new \Itkg\Batch\Mock\Hello();
      $this->object->setConfiguration(new \Itkg\Batch\Mock\Hello\Configuration());
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Itkg\Batch::run
     */
    public function testRun()
    {
        try {
            $this->object->run() ;     
        } catch(\Exception $e) {
            $this->fail('run ne pas doit renvoyer une exception ');
        }   
    }

    
    /**
     * @covers Itkg\Batch::init
     */
    public function testInit()
    {
        $this->object->init();
        $this->assertNotNull($this->object->getConfiguration());
    }

    /**
     * @covers Itkg\Batch::updateState
     */    
    public function testUpdateState() {
        $e = new \Exception();
        $this->object->updateState(10, 25, $e);
        $this->assertTrue($this->object->hasError());
        $this->assertEquals(15, $this->object->getState()->getDuration());
    }

    /**
     * @covers Itkg\Batch::hasError
     */    
    public function testHasError() {
        $this->assertFalse($this->object->hasError());
    }
}
