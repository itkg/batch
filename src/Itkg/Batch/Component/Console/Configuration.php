<?php

namespace Itkg\Batch\Component\Console;

use Itkg\Batch\Configuration as BaseConfiguration;

/**
 * Classe Configuration
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Configuration extends BaseConfiguration
{
    /**
     * Include path as string var
     *
     * @var string
     */
    protected $includePath;

    /**
     * Include list
     *
     * @var array
     */
    protected $includes;

    /**
     * Environment variables
     *
     * @var array
     */
    protected $env;

    /**
     * Execution directory
     *
     * @var type
     */
    protected $cwd;

    /**
     * Script timeout
     * Default 60s, 0 = infiny
     *
     * @var int
     */
    protected $timeout;

    /**
     * keys / values to override ini settings
     * @var array
     */
    protected $inis;

    /**
     * contrcutor
     */
    public function __construct()
    {
        $this->timeout = 60;
    }

    /**
     * Getter includePath
     *
     * @return string
     */
    public function getIncludePath()
    {
        return $this->includePath;
    }

    /**
     * Setter includePath
     *
     * @param string $includePath
     */
    public function setIncludePath($includePath)
    {
        $this->includePath = $includePath;
    }

    /**
     * Getter includes
     *
     * @return array
     */
    public function getIncludes()
    {
        if(!is_array($this->includes)) {
            $this->includes = array();
        }

        return $this->includes;
    }

    /**
     * Setter includes
     *
     * @param array $includes
     */
    public function setIncludes(array $includes = array())
    {
        $this->includes = $includes;
    }

    /**
     * Add an include to the stack
     *
     * @param array $include
     */
    public function addInclude($include)
    {
        $this->getIncludes();
        $this->includes[] = $include;
    }

    /**
     * Getter env
     *
     * @return array
     */
    public function getEnv()
    {
        if(!is_array($this->env)) {
            $this->env = array();
        }

        return $this->env;
    }

    /**
     * Setter env
     *
     * @param array $env
     */
    public function setEnv(array $env = array())
    {
        $this->env = $env;
    }

    /**
     * Add env var to the stack
     *
     * @param string $key
     * @param string $value
     */
    public function addEnv($key, $value)
    {
        $this->getEnv();
        $this->env[$key] = $value;
    }

    /**
     * Getter cwd
     *
     * @return string
     */
    public function getCwd()
    {
        return $this->cwd;
    }

    /**
     * Setter cwd
     *
     * @param string $cwd
     */
    public function setCwd($cwd)
    {
        $this->cwd = $cwd;
    }

    /**
     * Getter timeout
     *
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * Setter timeout
     * 0 = infiny
     *
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Getter ini set config
     *
     * @return array
     */
    public function getInis()
    {
        if(!is_array($this->inis)) {
            $this->inis = array();
        }
        return $this->inis;
    }

    /**
     * Setter ini set config
     *
     * @param array $inis
     */
    public function setInis(array $inis = array())
    {
        $this->inis = $inis;
    }

    /**
     * Add ini setting
     *
     * @param string $key
     * @param string $value
     */
    public function addIni($key, $value)
    {
         $this->getInis();
         $this->inis[$key] = $value;
    }


    /**
     * Render include path (for additional path)
     *
     * @return string
     */
    public function renderIncludePathAsAscript()
    {
        $includePath = '';
        if($this->getIncludePath()) {

            $includePath = 'set_include_path(get_include_path().\':'.$this->getIncludePath().'\');';

        }

        return $includePath;
    }

    /**
     * Render env variable
     *
     * @return string
     */
    public function renderEnvAsScript()
    {
        $env = '';

        if(is_array($this->getEnv())) {
            if(is_array($_ENV)) {
                $this->env = array_merge($_ENV, $this->env);
                foreach($this->env as $key => $value) {

                    $env .= '$_ENV[\''.$this->escapeVar($key).'\'] = \''.$this->escapeVar($value).'\';';
                }
            }
        }
        return $env;
    }

    /**
     * Render necessaries includes
     *
     * @return string
     */
    public function renderIncludesAsAscript()
    {
        $includes = '';
        foreach($this->getIncludes() as $include) {
            $includes .= 'require_once(\''.$include.'\');';
        }

        return $includes;
    }

    /**
     * Render ini set config
     *
     * @return string
     */
    public function renderInisAsScript()
    {
        $inis = '';
        foreach($this->getInis() as $key =>  $value) {
            $inis .= 'ini_set(\''.$this->escapeVar($key).'\', '.$this->escapeVar($value).');';
        }

        return $inis;
    }

    /**
     * Escape a var
     *
     * @param string $var
     * @return string
     */
    protected function escapeVar($var)
    {
        return str_replace("'", "\\'", $var);
    }
}