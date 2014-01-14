<?php

namespace Itkg\Batch;

use Itkg\Exception\NotFoundException;

/**
 * Classe de création des Batch
 * Génère un batch et l'initialise gràce aux paramêtres définis 
 * 
 * @author Pascal DENIS <pascal.denis@businessdecision.com>
 * 
 * @package \Itkg\Batch
 */
class Factory 
{
    /**
     * Renvoi un batch dont la clé est passée en paramêtre
     * Charge l'ensemble de la configuration liée au batch
     * 
     * @static
     * @param string $batch La clé du batch
     * @param array $parameters La liste des paramêtres 
     * @return \Itkg\Batch
     */
    public static function getBatch($batch) 
    {
        // Instanciation du batch par définition si celle-ci existe
        if(isset(\Itkg::$config[$batch]['class'])) {
            $oBatch = new \Itkg::$config[$batch]['class'];
            $sBatchClass = \Itkg::$config[$batch]['class'];
        }else {
            
            // Sinon on va parser la clé pour retrouver le batch associé
            $sPackage = \preg_replace('/BATCH.*/', '', str_replace('_', '', $batch));
            $sClass = \preg_replace('/.*_BATCH_/', '', $batch);
            $aTemp = \explode('_', $sClass);
            $sBatchClass = \ucfirst(strtolower($sPackage)).'\\Batch\\';
            
            if(is_array($aTemp)) {
                foreach($aTemp as $sValue) {
                    $sBatchClass .= \ucfirst(strtolower($sValue));
                }
            }else {
                $sBatchClass .= \ucfirst(strtolower($sClass));
            }
            // Instanciation du batch par son nom
            if(class_exists($sBatchClass)) {
                $oBatch = new $sBatchClass;
            }
        }
        
        if(!isset($oBatch) || !is_object($oBatch)) {
            throw new NotFoundException('Le batch '.$batch.' n\'existe pas car la classe '.$sServiceClass.' n\'est pas définie');
        }

        /**
         * Chargement de la configuration depuis la définition du batch si elle existe
         */
        if(isset(\Itkg::$config[$batch]['configuration'])) {
            $oConfiguration = new \Itkg::$config[$batch]['configuration'];
        }else {
            /**
             * Chargement de la configuration en essayant d'instancié la classe 
             * Batch\NomBatch\Configuration
             */
            $sConfigurationClass = $sBatchClass.'\Configuration';
            if(class_exists($sConfigurationClass)) {
                $oConfiguration = new $sConfigurationClass;
            }

        }
        
        // La classe de configuration est obligatoire
        if(!is_object($oConfiguration)) {
            throw new \Itkg\Exception\NotFoundException('La classe de configuration du batch '.$batch.' n\'existe pas car la classe '.$sConfigurationClass.' n\'est pas définie');
        }

        /**
         * Chargement des paramètres de configuration du service
         * Utile pour les identifiants de WS ou d'autres parametres dépendant de l'environnement
         */
        if(isset(\Itkg::$config[$batch]['PARAMETERS'])) {
            $oConfiguration->loadParameters(\Itkg::$config[$batch]['PARAMETERS']);
        }
        
        $oConfiguration->init();
       
        // Chargement de la configuration
        $oBatch->setConfiguration($oConfiguration);
        
        return $oBatch;
    }	
}
