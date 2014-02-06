<?php

namespace Itkg\Batch;

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
     * @throws \UnexpectedValueException
     * @return \Itkg\Batch
     */
    public static function getBatch($batch) 
    {
        $oBatch = null;

        // Instanciation du batch par définition si celle-ci existe
        if(isset(\Itkg\Batch::$config[$batch]['class'])) {
            $oBatch = new \Itkg\Batch::$config[$batch]['class'];
            $sBatchClass = \Itkg\Batch::$config[$batch]['class'];
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
        
        if($oBatch == null) {
            throw new \UnexpectedValueException(
                sprintf(
                    'Le batch %s n\'existe pas car la classe %s n\'est pas définie',
                    $batch,
                    $sBatchClass
                )
            );
        }

        $configuration = self::getConfiguration($batch, $sBatchClass);

        // Chargement de la configuration
        $oBatch->setConfiguration($configuration);
        
        return $oBatch;
    }

    /**
     * Load batch configuration
     *
     * @param string $key Batch KEY
     * @param string $batchClass Batch class
     * @throws \UnexpectedValueException
     * @return Configuration
     */
    public static function getConfiguration($key, $batchClass)
    {
        $oConfiguration = null;
        $sConfigurationClass = '';
        /**
         * Chargement de la configuration depuis la définition du batch si elle existe
         */
        if(isset(\Itkg\Batch::$config[$key]['configuration'])) {
            $oConfiguration = new \Itkg\Batch::$config[$key]['configuration'];
        }else {
            /**
             * Chargement de la configuration en essayant d'instancié la classe
             * Batch\NomBatch\Configuration
             */
            $sConfigurationClass = $batchClass.'\Configuration';
            if(class_exists($sConfigurationClass)) {
                $oConfiguration = new $sConfigurationClass;
            }

        }

        // La classe de configuration est obligatoire
        if(!is_object($oConfiguration)) {
            throw new \UnexpectedValueException(
                sprintf(
                    'La classe de configuration du batch %s n\'existe pas car la classe %s n\'est pas définie',
                    $key,
                    $sConfigurationClass
                )
            );
        }

        /**
         * Chargement des paramètres de configuration du service
         * Utile pour les identifiants de WS ou d'autres parametres dépendant de l'environnement
         */
        if(isset(\Itkg\Batch::$config[$key]['PARAMETERS'])) {
            $oConfiguration->loadParameters(\Itkg\Batch::$config[$key]['PARAMETERS']);
        }

        $oConfiguration->init();

        return $oConfiguration;
    }
}
