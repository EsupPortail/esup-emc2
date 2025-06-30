<?php

/** Requête utilisée pour la collecte des données **/
//select e.code, e.libelle, e.description, e.parametres, e.recursion
//from unicaen_evenement_type e

$csvFile = fopen("./database/sources/csv/unicaen_evenement_type.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'code' => $item[0],
            'libelle' => $item[1],
            'description' => $item[2],
            'parametres' => $item[3],
            'recursion' => $item[4],
        ];
        $array["unicaen_evenement_type"][] = $instance;
    }
}

fclose($csvFile);

return $array;