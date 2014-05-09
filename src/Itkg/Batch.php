<?php

namespace Itkg;

/**
 * Classe Batch
 * 
 * Cette classe permet la création des batch et la gestion des différents 
 * mécanismes qui y sont liés : logs, notifications, sauvegarde
 * 
 * Chaque batch doit être lancé via la méthode run. Le reste du process est 
 * automatique 
 * 
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
abstract class Batch 
{
    /**
     * Configuration du batch
     * 
     * @var \Itkg\Batch\Configuration
     */
    protected $configuration;
    
    /**
     * Etat du batch
     * 
     * @var \Itkg\Batch\State
     */
    protected $state;
    
    /**
     * Message utilisé comme corps de log
     * 
     * @var string 
     */
    protected $message;
    
    /**
     * Id du batch permettant une uniformisation au sein des différents loggers
     * 
     * @var string 
     */
    protected $id;

    /**
     * Batch config container
     *
     * @var array
     */
    public static $config = array(
        'LOG_PATH'          => '/var/logs',
        'DEFAULT_FORMATTER' => 'string',
        'TEMP_ROOT'         => '/tmp',
        'WRITERS'           => array(
            'syslog'    => 'Itkg\Log\Writer\SysLogWriter',
            'error_log' => 'Itkg\Log\Writer\ErrorLogWriter',
            'echo'      => 'Itkg\Log\Writer\EchoWriter',
            'soap'      => 'Itkg\Log\Writer\SoapWriter',
            'file'      => 'Itkg\Log\Writer\FileWriter'
        ),
        'FORMATTERS'        => array(
            'simple' => 'Itkg\Log\Formatter\SimpleFormatter',
            'string' => 'Itkg\Log\Formatter\StringFormatter',
            'xml'    => 'Itkg\Log\Formatter\XMLFormatter'
        ),

    );
    
    /**
     * Méthode d'initialisation d'un batch
     */
    public function init(){}
        
    /**
     * Démarre le batch
     * Cette méthode déclenche l'ensemble des actions du batch
     * C'est cette méthode qui doit être appelée pour exécuter le batch
     */
    public function run()
    {
        // Initialisation du batch
        $this->init();
        
        // Initialisation des loggers
        $this->getConfiguration()->initLoggers();
        
        $exception = null;
        $start = microtime();
        
        try {
            // Execution du batch
            $this->execute();
        }catch(\Exception $e) {
            $exception = $e;
            $this->message = $e->getMessage();
        }
        
        $end = microtime();
        
        // Mise à jour de l'état du batch
        $this->updateState($start, $end, $exception);

        // Log du batch
        $this->report();
        
        // Notifications
        $this->notify();
        
        // Sauvegarde
        $this->save();
    }
    
    /**
     * Méthode à exécuter pour chaque batch
     */
    protected abstract function execute();
    
    /**
     * @codeCoverageIgnore
     * Déclenche l'ensemble des notifiers
     */
    protected function notify()
    {
        foreach($this->configuration->getNotifiers() as $notifier) {
            $notifier->notify();
        }
    }
    
    /**
     * @codeCoverageIgnore
     * Déclenche l'ensemble des loggers
     */
    protected function report()
    {
        // Tous les loggers vont écrire le résultat du batch
        foreach($this->configuration->getLoggers() as $logger) {
            if($this->id) {
                // L'identifiant de log est unique au sein de tous les loggers
                $logger->setId($this->id);
            }

            $logger->addInfo($this->message, array('requestTime' => $this->state->getDuration()));

        }
    }
    
    /**
     * Méthode appelée à la fin du traitement
     * Peut effectuer une sauvegarde de l'état du batch
     */
    protected function save(){}
    
    /**
     * Getter configuration
     * 
     * @return \Itkg\Batch\Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
    
    /**
     * Setter configuration
     * 
     * @param \Itkg\Batch\Configuration $configuration
     */
    public function setConfiguration(\Itkg\Batch\Configuration $configuration)
    {
        $this->configuration = $configuration;
    }
    
    /**
     * Getter state
     * @codeCoverageIgnore
     * @return \Itkg\Batch\State
     */
    public function getState()
    {
        return $this->state;
    }
    
    /**
     * Setter state
     * @codeCoverageIgnore
     * @param \Itkg\Batch\State $state
     */
    public function setState(\Itkg\Batch\State $state = null) 
    {
        $this->state = $state;
    }
     
    /**
     * Initie un nouveau state ou met le state courant à jour
     * 
     * @param int $start
     * @param int $end
     * @param \Exception $exception
     */
    public function updateState($start, $end, $exception = null)
    {
        if(!is_object($this->state)) {
            $this->state = new \Itkg\Batch\State();
        }
        
        $this->state->setStart($start);
        $this->state->setEnd($end);
        $this->state->setException($exception);
    }
    
    /**
     * Getter Id
     * @codeCoverageIgnore
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Setter Id
     * @codeCoverageIgnore
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
    /**
     * Getter message
     * @codeCoverageIgnore
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
    
    /**
     * Setter message
     * @codeCoverageIgnore
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
    
    /**
     * Indique si le batch est en erreur
     * 
     * @return boolean 
     */
    public function hasError()
    {
       return (is_object($this->state) && $this->state->getStatus() != 0); 
    }
}