<?php

/** Requête utilisée pour la collecte des données **/
//select e.code, e.libelle, e.refusable
//from unicaen_validation_type e

$csvFile = fopen("./database/sources/csv/unicaen_validation_type.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'code' => $item[0],
            'libelle' => $item[1],
            'refusable' => ($item[2] === true)? "t": "f",
        ];
        $array["unicaen_validation_type"][] = $instance;
    }
}

fclose($csvFile);

return $array;