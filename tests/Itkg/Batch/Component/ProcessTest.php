<?php

namespace Itkg\Batch\Component;


/**
 * Classe Process
 *
 * Cette classe permet de gérer les commandes systèmes 
 * de traiter les erreurs, d'obtenir un rapport complet sur les différentes commandes
 * exécutées
 * 
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class ProcessTest extends \PHPUnit_Framework_TestCase
{
     /**

     * @var Itkg\Configuration
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
     * Get parameters
     *
     * @covers Itkg\Batch\Component\Process::__construct
     */
    public function test__construct()
    {
      $this->object = new \Itkg\Batch\Component\Process("test");
      $this->assertInstanceOf("\Itkg\Batch\Component\Process", $this->object);
    }
}