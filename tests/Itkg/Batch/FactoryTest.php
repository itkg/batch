<?php
namespace Itkg\Batch;

use Itkg\Batch\Factory;

/**
 * Class de test Factory
 *
 * @author Jean-Baptiste ROUSSEAU <jean-baptiste.rousseau@businessdecision.com>
 *
 * @package \Itkg\Batch
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Factory
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Factory;
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
     * @covers Itkg\Batch\Factory::getBatch
     * @covers Itkg\Batch\Factory::getConfiguration
     */
    public function testGetBatch()
    {
                //cas du batch null -----------------------------------
        $batch = null;
        try {
            $this->object->getBatch($batch);
            $this->fail('getBatch doit renvoyer une exception Itkg\Exception\NotFoundException');
        } catch(\Exception $e) {
             $this->assertEquals($e->getMessage(), "Le batch  n'existe pas car la classe \Batch\ n'est pas définie");
             $this->assertEquals('UnexpectedValueException', get_class($e));
        }
        //cas du batch qui n'existe pas -----------------------------------
        $batch = 'test';
        try {
            $this->object->getBatch($batch);
            $this->fail('getBatch doit renvoyer une exception Itkg\Exception\NotFoundException');
        } catch(\Exception $e) {

             $this->assertEquals($e->getMessage(), "Le batch test n'existe pas car la classe Test\Batch\Test n'est pas définie");
             $this->assertEquals('UnexpectedValueException', get_class($e));
        }
        //cas OK -----------------------------------        
        $batch = 'MY_BATCH';        
        try {
            $this->object->getBatch($batch);
        } catch(\Exception $e) {
            $this->fail('getBatch ne doit pas renvoyer d\'exception'.$e->getMessage());
        }
        //cas de la config qui n'existe pas -----------------------------------
        $batch = 'MY_BATCH';   
        \Itkg\Batch::$config['MY_BATCH']['configuration'] = null;         
        try {
            $this->object->getBatch($batch);
        } catch(\Exception $e) {
            $this->fail('getBatch ne doit pas renvoyer une exception ' . $e->getMessage());
        }

    }
}