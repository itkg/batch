<?php

namespace Itkg\Batch\Mock\Hello;

use Itkg\Batch\Configuration as BaseConfiguration;

/**
 * Classe Configuration
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Configuration extends BaseConfiguration
{
    public function __construct()
    {
        $this->identifier = '[HELLO]';
        
        $this->getLoggers();
        $this->loggers[] = array('writer' => 'echo');
        $this->parameters = array();
    }
}
