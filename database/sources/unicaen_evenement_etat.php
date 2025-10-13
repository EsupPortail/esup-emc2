<?php

/** Requête utilisée pour la collecte des données **/
//select code, libelle, description from  unicaen_evenement_etat

$csvFile = fopen("./database/sources/csv/unicaen_evenement_etat.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'code' => $item[0],
            'libelle' => $item[1],
            'description' => $item[2],
        ];
        $array["unicaen_evenement_etat"][] = $instance;
    }
}

fclose($csvFile);

return $array;