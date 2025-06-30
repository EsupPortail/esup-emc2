<?php

/** Requête utilisée pour la collecte des données **/
//select c.code, c.libelle, c.description
//from unicaen_autoform_formulaire c
//where c.histo_destruction IS NULL
//order by c.code

$csvFile = fopen("./database/sources/csv/unicaen_autoform_formulaire.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'code' => $item[0],
            'libelle' => $item[1],
            'description' => $item[2],
        ];
        $array["unicaen_autoform_formulaire"][] = $instance;
    }
}

fclose($csvFile);

return $array;