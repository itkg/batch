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
     * Chaine de caractère représentant un include path
     *
     * @var string
     */
    protected $includePath;

    /**
     * Liste d'includes
     *
     * @var array
     */
    protected $includes;

    /**
     * Variables d'environnements
     *
     * @var array
     */
    protected $env;

    /**
     * Répertoire d'execution
     *
     * @var type
     */
    protected $cwd;

    /**
     * timeout du script
     * Doit être initialisé à 0 pour supprimer le timeout
     * Valeur par défaut : 60 secondes
     *
     * @var int
     */
    protected $timeout;

    /**
     * Liste de clés / valeurs utilisés pour surcharger
     * la configuration de php.ini et modifiable via ini_set
     *
     * @var array
     */
    protected $inis;

    /**
     * Constructeur
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
     * Ajoute un include à la pile
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
     * Ajoute une variable d'environnement à la pile
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
     * 0 = illimité
     *
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * Getter inis
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
     * Setter inis
     *
     * @param array $inis
     */
    public function setInis(array $inis = array())
    {
        $this->inis = $inis;
    }

    /**
     * Ajoute un paramètre de configuration à la pile
     *
     * @param string $key
     * @param string $value
     */
    public function addIni($key, $value)
    {
         $this->getInis();
         $this->inis[$key] = $value;
    }


    public function renderIncludePathAsAscript()
    {
        $includePath = '';
        if($this->getIncludePath()) {

            $includePath = 'set_include_path(get_include_path().\':'.$this->getIncludePath().'\');';

        }

        return $includePath;
    }

    public function renderEnvAsScript()
    {
	$env = '';

	if(is_array($this->getEnv())) {
        if(is_array($_ENV)) {
            $this->env = array_merge($_ENV, $this->env);
            foreach($this->env as $key => $value) {
                $env .= '$_ENV[\''.$key.'\'] = \''.$value.'\';';
            }
        }
	}
	return $env;
    }
    public function renderIncludesAsAscript()
    {
        $includes = '';
        foreach($this->getIncludes() as $include) {
            $includes .= 'require_once(\''.$include.'\');';
        }

        return $includes;
    }

    public function renderInisAsScript()
    {
        $inis = '';
        foreach($this->getInis() as $key =>  $value) {
            $inis .= 'ini_set(\''.$key.'\', '.$value.');';
        }

        return $inis;
    }
}