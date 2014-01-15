<?php

namespace Itkg\Batch\Component\Console;

use Itkg\Batch\Component\Console;
use Itkg\Log\IdGenerator;
use Itkg\Batch\Factory;
/**
 * Classe Batch
 *
 * Classe permettant via la console de lancer des batch dans un process php
 * isolé
 *
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 */
class Batch extends Console
{

    /**
     * La clé identifiant le batch
     *
     * @var type
     */
    protected $name;

    /**
     * Id du process
     *
     * Cet Id servira d'identifiants pour les tous les logs associés au traitement
     * de la console (ainsi que les logs du process exécuté)
     *
     * @var string
     */
    protected $id;

    /**
     * Batch à exécuter
     *
     * @var \Itkg\Batch
     */
    protected $batch;

    /**
     * Constructeur
     *
     * @param array $args
     * @throws \Itkg\Exception\NotFoundException
     */
    public function __construct(array $args = array())
    {
        // Si aucun argument n'est passé, on lève une exception
        if(empty($args)) {
            throw new \Itkg\Exception\NotFoundException('Le nom du batch n\'a pas été renseigné');
        }

        // On récupère la clé du batch
        $this->name = $args[0];

        // On récupère le batch (Permet de vérifier que le batch existe
        // et de récupérer sa configuration)
        $this->batch = Factory::getBatch($this->name);

        // Initialisation de la configuration
        $this->getConfiguration();

        // Initialisation de l'ID qui sera utilisé tout au long du process
        $this->setId(
            IdGenerator::generate().' - '.
            $this->getConfiguration()->getIdentifier().
            $this->batch->getConfiguration()->getIdentifier().' '
        );
    }

    /**
     * Getter name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setter name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Getter ID
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter ID
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Retourne le script à executer
     *
     * @return string
     */
    protected function getScript()
    {
        return
            '<?php
		'.$this->getConfiguration()->renderEnvAsScript()
                .$this->getConfiguration()->renderInisAsScript()
                .$this->getConfiguration()->renderIncludePathAsAscript()
                .$this->getConfiguration()->renderIncludesAsAscript().'
                $batch = \Itkg\Batch\Factory::getBatch(\''.$this->name.'\');
                $batch->setId(\''.$this->getId().'\');
                $batch->run();
            ?>'
        ;
    }

    /**
     * Ecrit le rapport dans les différents logs
     */
    public function report()
    {
        foreach($this->getConfiguration()->getLoggers() as $logger) {
            if($this->id) {
                $logger->setId($this->id);
            }
            
            $logger->write($this->message);
        }
    }
}
