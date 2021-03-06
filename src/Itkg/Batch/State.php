<?php

namespace Itkg\Batch;

/**
 * Classe State
 * 
 * Modélise l'état d'un batch
 * 
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class State 
{
    /**
     * Statut d'execution (1 ou 0)
     * @var int
     */
    protected $status;
    
    /**
     * Exception pouvant être levée lors de l'execution du batch
     * @var \Exception
     */
    protected $exception;
    
    /**
     * Date d'execution
     * @var \DateTime
     */
    protected $date;
    
    /**
     * Début d'execution
     * @var int
     */
    protected $start;
    
    /**
     * Fin d'execution
     * @var int
     */
    protected $end;

    /**
     * Constructeur
     */
    public function __construct()
    {
        $this->status = 0;
        $this->date = new \DateTime();
    }
    
    /**
     * Getter exception
     * 
     * @codeCoverageIgnore
     * @return \Exception|null
     */
    public function getException()
    {
        return $this->exception;
    }
    
    /**
     * Getter status
     * 
     * @codeCoverageIgnore
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }
    
    /**
     * Getter date
     * 
     * @codeCoverageIgnore
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
    
    /**
     * Retourne la durée d'exécution 
     */
    public function getDuration()
    {
        return ($this->end - $this->start);
    }
    
    /**
     * Getter end
     * 
     * @codeCoverageIgnore
     * @return int
     */
    public function getEnd()
    {
        return $this->end;
    }
    
    /**
     * Getter start 
     * 
     * @codeCoverageIgnore
     * @return int
     */
    public function getStart()
    {
        return $this->start;
    }
     
    /**
     * Setter date
     * 
     * @codeCoverageIgnore
     * @param \DateTime $datetime
     */
    public function setDate(\DateTime $datetime) 
    {
        $this->date = $datetime;
    }
    
    /**
     * Setter exception
     * 
     * @param \Exception $exception
     */
    public function setException(\Exception $exception = null)
    {
        $this->exception = $exception;
        if($this->exception) {
            $this->status = 1;
        }
    }
    
    /**
     * Setter status
     * 
     * @codeCoverageIgnore
     * @param int $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Setter start
     * 
     * @codeCoverageIgnore
     * @param int $start
     */
    public function setStart($start) 
    {
        $this->start = $start;
    }
    
    /**
     * Setter end
     * 
     * @codeCoverageIgnore
     * @param int $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }
}

