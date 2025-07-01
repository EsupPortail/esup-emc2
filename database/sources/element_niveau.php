<?php

/** Requête utilisée pour la collecte des données **/
//select type, libelle, niveau, description
//from element_niveau

$csvFile = fopen("./database/sources/csv/element_niveau.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'type' => $item[0],
            'libelle' => $item[1],
            'niveau' => $item[2],
            'description' => $item[3],
        ];
        $array["element_niveau"][] = $instance;
    }
}

fclose($csvFile);

return $array;