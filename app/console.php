<?php

// Ce lanceur console peut etre lancé directement au sein du répertoire itkg
require_once(__DIR__.'/../vendor/autoload.php');

// On s'assure que au moins un argument est présent (le libellé de la commande à exécuter)
if(empty($argv) || sizeof($argv) < 2) {
    echo "Aucune commande fournie\n";
    echo "php app/console command args\n";
    return;
}

$command = ucFirst($argv[1]);
unset($argv[0]);
unset($argv[1]);
$class = 'Itkg\\Component\\Console\\'.$command;

if(class_exists($class)) {
    // Si la commande est connue, on execute le script
    $console = new $class(array_values($argv));
    $console->run(); 
}else {
    // Si la commande est inconnue 
    echo "Commande fournie invalide\n";
    echo "php app/console command args\n";
    return;
}
