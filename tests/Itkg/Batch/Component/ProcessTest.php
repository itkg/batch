<?php
namespace Itkg\Batch\Component;

/**
 * Class de test Process
 *
 * @author Jean-Baptiste ROUSSEAU <jean-baptiste.rousseau@businessdecision.com>
 *
 * @package Itkg\Batch\Component
 */
class ProcessTest extends \PHPUnit_Framework_TestCase
{
     /**
     * @var Itkg\Batch\Component\Process
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {         
      $this->object = new \Itkg\Batch\Component\Process("test");
    }
    
    /**
     * __construct
     *
     * @covers Itkg\Batch\Component\Process::__construct
     */
    public function test__construct()
    {
      $this->object = new \Itkg\Batch\Component\Process("test");
      $this->assertInstanceOf("\Itkg\Batch\Component\Process", $this->object);
    }
}