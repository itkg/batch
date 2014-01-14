<?php

namespace Itkg\Batch\Component\Process;

use Symfony\Component\Process\ProcessBuilder;

/**
 * Classe PhpProcessBuilder
 *
 * Manage les process PHP 
 * 
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class PhpProcessBuilder extends ProcessBuilder
{
    /**
     * Récupération du process (après création)
     * 
     * @return \Symfony\Component\Process\PhpProcess
     * @throws LogicException
     */
    public function getProcess()
    {
        if (!count($this->arguments)) {
            throw new LogicException('Le script à exécuter est vide');
        }

        $options = $this->options;

        $script = implode(' ', array_map('escapeshellarg', $this->arguments));

        if ($this->inheritEnv) {
            $env = $this->env ? $this->env + $_ENV : null;
        } else {
            $env = $this->env;
        }

        return new PhpProcess($script, $this->cwd, $env, $this->stdin, $this->timeout, $options);
    }
}

