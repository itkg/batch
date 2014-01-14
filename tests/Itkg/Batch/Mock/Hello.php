<?php

namespace Itkg\Batch\Mock;

use Itkg\Batch;

/**
 * Classe Hello
 * 
 * Cette classe est un mock pour tester les batch
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Hello extends Batch
{
    protected function execute() 
    {
        $this->message =  'it works';
    }
}
