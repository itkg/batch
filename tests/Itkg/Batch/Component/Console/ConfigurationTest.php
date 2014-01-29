<?php
namespace Itkg\Batch\Component\Console;

/**
 * Class de test Factory
 *
 * @author Jean-Baptiste ROUSSEAU <jean-baptiste.rousseau@businessdecision.com>
 *
 * @package \Itkg\Batch
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase  {

    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new \Itkg\Batch\Component\Console\Configuration();
        $this->object->setIdentifier("[BULK BATCH]");
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * __construct
     *
     * @covers Itkg\Batch\Component\Console\Configuration::__construct
     */
    public function test__construct()
    {

      $this->assertEquals(60, $this->object->getTimeout());
    }
    
    /**
     *
     * @covers Itkg\Batch\Component\Console\Configuration::renderInisAsScript
     */
    public function testRenderInisAsScript()
    {
      $this->object->setInis(array("myprop"=> "myval'"));
      $this->assertEquals("ini_set('myprop', myval\');", $this->object->renderInisAsScript());
    }
    
     /**
     *
     * @covers Itkg\Batch\Component\Console\Configuration::renderIncludesAsAscript
     */
    public function testRenderIncludesAsAscript()
    {
      $this->object->setIncludes(array("myval"));
      $this->assertEquals("require_once('myval');", $this->object->renderIncludesAsAscript());
    }   
    /**
     * __construct
     *
     * @covers Itkg\Batch\Component\Console\Configuration::renderEnvAsScript
     */
    public function testRenderEnvAsScript()
    {
      $this->object->setEnv(array("myprop"=> "myval"));
      $_ENV["test3"] = "value3";
      
      $stringtotest="_ENV['myprop'] = 'myval';";
      $env = $this->object->renderEnvAsScript();
      $this->assertTrue(strpos($env, $stringtotest)>0);
    }   
     /**
     *
     * @covers Itkg\Batch\Component\Console\Configuration::renderIncludePathAsAscript
     */
    public function testRenderIncludePathAsAscript()
    {
      $this->object->setIncludePath("test");
      $this->assertEquals("set_include_path(get_include_path().':test');", $this->object->renderIncludePathAsAscript());
    }      
    
     /**
     *
     * @covers Itkg\Batch\Component\Console\Configuration::getInis
     */
    public function testGetInis()
    {
      $this->assertInternalType("array", $this->object->getInis());
    }    
    /**
     *
     * @covers Itkg\Batch\Component\Console\Configuration::getEnv
     */
    public function testGetEnv()
    {
      $this->assertInternalType("array", $this->object->getEnv());
    } 
    
        /**
     *
     * @covers Itkg\Batch\Component\Console\Configuration::addInclude
     */
    public function testAddInclude()
    {
      $this->object->addInclude("test");
      $this->assertInternalType("array", $this->object->getIncludes());
      $this->assertEquals(1, count($this->object->getIncludes()));
    } 
            /**
     *
     * @covers Itkg\Batch\Component\Console\Configuration::addEnv
     */
    public function testAddEnv()
    {
      $this->object->addEnv("test", "value");
      $this->assertInternalType("array", $this->object->getEnv());
      $this->assertEquals(1, count($this->object->getEnv()));
    }
    /**
     *
     * @covers Itkg\Batch\Component\Console\Configuration::addIni
     */
    public function testAddIni()
    {
      $this->object->addIni("test", "value");
      $this->assertInternalType("array", $this->object->getInis());
      $this->assertEquals(1, count($this->object->getInis()));
    }    
}

?>
