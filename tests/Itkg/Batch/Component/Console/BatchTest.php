<?php

namespace Itkg\Batch\Component\Console;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
use Itkg\Log\Handler\EchoHandler;

/**
 * Classe BatchTest
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class BatchTest extends \PHPUnit_Framework_TestCase
{
 /**
     * @var Itkg\Batch\Component\Console\Batch
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        \Itkg\Log::$config['DEFAULT_HANDLER'] = new EchoHandler();
        \Itkg\Batch::$config['MY_BATCH']['PARAMETERS'] = array(
            'location' => 'http://MOCK_IP/mockservice',
            'signature' => 'http://MOCK_IP/signature',
            'login' => 'MOCK_LOGIN',
            'password' => 'MOCK_PASSWORD',
            'mustunderstand' => 1,
            'timeout' => 10,
            'namespace' => 'http://MOCK_NAMESPACE',
            'wsdl' => '/MOCK_PATH_WSDL.wsdl'            
        );
        \Itkg\Batch::$config['MY_BATCH']['class'] = 'Itkg\Batch\Mock\Hello';
        \Itkg\Batch::$config['MY_BATCH']['configuration'] = 'Itkg\Batch\Mock\Hello\Configuration';
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }
 
    /**
     * @covers Itkg\Batch\Component\Console\Batch::__construct
     */ 
    public function test__construct()
    {
        $batch = null;
        try {
            $this->object = new Batch;
            $this->fail('getBatch doit renvoyer une exception Exception');
        } catch(\Exception $e) {
             $this->assertEquals($e->getMessage(), "Le nom du batch n'a pas été renseigné");
             $this->assertEquals('Exception', get_class($e));
        }   
        $batch = array("MY_BATCH");
        try {
            $this->object = new Batch($batch);
        } catch(\Exception $e) {
             $this->fail('__construct ne doit pas renvoyer d\'exception');
        }   
    }
    /**
     * @covers Itkg\Batch\Component\Console\Batch::report
     */
    public function testReport() 
    {
        $script = "";
        $this->object = new Batch(array("MY_BATCH"));
        $this->object->getConfiguration()->addLogger(\Itkg\Log\Factory::getLogger(array(array('handler' => new EchoHandler()))), "test");
        $this->assertEquals($script, $this->object->report());
    }
}
