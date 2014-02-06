<?php

// Ne peut être éxecuté seul et doit être inclus au sein d'un autre lanceur 
// pour être fonctionnel
// 
// On s'assure que au moins un argument est présent (le libellé de la commande à exécuter)
if(empty($argv) || sizeof($argv) < 2) {
    echo "Please, provide a command\n";
    echo "php app/console command args\n";
    exit();
}

$command = ucFirst($argv[1]);
unset($argv[0]);
unset($argv[1]);
$class = 'Itkg\\Batch\\Component\\Console\\'.$command;

if(class_exists($class)) {
    // Si la commande est connue, on execute le script
    $console = new $class(array_values($argv));
    $console->run(); 
}else {
    // Si la commande est inconnue 
    echo "Unknown command\n";
    echo "php app/console command args\n";
    exit();
}
