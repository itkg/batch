<?php

namespace Itkg\Batch\Mock;

use Itkg\Component\Console\Configuration as BaseConfiguration;

/**
 * Classe Configuration
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Configuration extends BaseConfiguration
{
    public function __construct()
    {
        $this->identifier = '[BATCH]';
        $this->cwd = '/var/www/itkg';
        $this->addEnv('TYPE_ENVIRONNEMENT', 'DEV');
        $includes = array(
            '/var/www/itkg/vendor/autoload.php',
        );
        $this->addIni('display_errors', 1);
        $this->setIncludes($includes);
        
        $this->loggers[] = array(
            'writer' => 'file',
            'parameters' => array(
                'file' => '/tmp/batch.txt'
            )
        );
    }
}
