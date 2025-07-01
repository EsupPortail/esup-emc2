<?php

/** Requête utilisée pour la collecte des données **/
//select c.code, c.libelle, c.refusable
//from unicaen_validation_type c
//order by c.code

$csvFile = fopen("./database/sources/csv/unicaen_evenement_etat.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'code' => $item[0],
            'libelle' => $item[1],
            'refusable' => ($item[2] === true) ? "t":"f",
        ];
        $array["unicaen_evenement_etat"][] = $instance;
    }
}

fclose($csvFile);

return $array;