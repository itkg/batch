<?php

namespace Itkg\Batch\Component;

use \Symfony\Component\Process\PhpProcess;

/**
 * Classe Console
 *
 * Cette classe permet de traiter des actions effectuées depuis une console
 * via un process PHP interne
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
abstract class Console
{
    /**
     * Configuration de la console
     *
     * @var \Itkg\Component\Console\Configuration
     */
    protected $configuration;

    /**
     * Process PHP
     *
     * @var \Symfony\Component\Process\PhpProcess
     */
    protected $process;

    /**
     * Résumé du traitement à logguer
     *
     * @var string
     */
    protected $message;

    /**
     * Retourne le script PHP associé
     *
     * @return string
     */
    protected abstract function getScript();

    /**
     * Méthode d'execution
     */
    public function run()
    {
        // Initialisation des loggers
        $this->getConfiguration()->initLoggers();
        // Initialisation du process
        $this->createProcess();

        try {
            // Lancement du process
            $this->process->run(function ($type, $buffer) {
                // On affiche le debug à l'écran pour s'assurer qu'aucune erreur
                // bloquante n'est présente et suivre l'exécution des longs process
                if ('err' === $type) {
                    echo 'ERR > '.$buffer;
                } else {
                    echo 'OUT > '.$buffer;
                }
            });

            // On récupère la sortie standard en cas de succès (STDOUT)
            if($this->process->isSuccessful()) {
                $this->message .= $this->process->getOutput();
            }else {
                // On récupère la sortie standard en cas d'erreur (STDERR)
                $this->message .= $this->process->getErrorOutput();
            }
        }catch(\Exception $e) {
            $this->message .= $e->getMessage();
        }

        // Log du process
        $this->report();

        // Envoi des notifications
        $this->notify();
    }

    /**
     * Getter configuration
     *
     * @return \Itkg\Component\Console\Configuration
     */
    public function getConfiguration()
    {
        if(!is_object($this->configuration)) {
            if(isset(\Itkg\Batch::$config['CONFIGURATION']) && class_exists(\Itkg\Batch::$config['CONFIGURATION'])) {
                $this->configuration = new \Itkg\Batch::$config['CONFIGURATION'];
            }else {
                $this->configuration = new \Itkg\Component\Console\Configuration();
            }
        }
        return $this->configuration;
    }

    /**
     * Setter configuration
     *
     * @param \Itkg\Component\Console\Configuration $configuration
     */
    public function setConfiguration(\Itkg\Component\Console\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    /**
     * Getter process
     *
     * @return \Symfony\Component\Process\PhpProcess
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * Setter process
     *
     * @param \Symfony\Component\Process\PhpProcess $process
     */
    public function setProcess(\Symfony\Component\Process\PhpProcess $process)
    {
        $this->process = $process;
    }

    /**
     * Getter message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Setter message
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Create process
     */
    protected function createProcess()
    {
        // initialisation du process
        $this->process = new PhpProcess(
            $this->getScript(),
            $this->getConfiguration()->getCwd(),
            $this->getConfiguration()->getEnv(),
            $this->getConfiguration()->getTimeout()
        );

        if($this->configuration->getParameter('PHP_BINARY')) {
            $this->process->setPhpBinary($this->configuration->getParameter('PHP_BINARY'));
        }
    }

    /**
     * Déclenche l'ensemble des notifiers
     */
    protected function notify()
    {
        foreach($this->getConfiguration()->getNotifiers() as $notifier) {
            $notifier->notify();
        } 
    }

    /**
     * Déclenche l'ensemble des loggers
     */
    protected function report()
    {
        foreach($this->getConfiguration()->getLoggers() as $logger) {
            $logger->write($this->message);
        }
    }
}