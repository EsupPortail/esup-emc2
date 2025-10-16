<?php

/** Requête utilisée pour la collecte des données **/
//select t.code as code, t.libelle as libelle, t.description as description, t.usage as usage, t.example_options as example_options, t.example_texte as example_texte, t.example_reponse as example_reponse
//from unicaen_autoform_champ_type t

$csvFile = fopen("./database/sources/csv/unicaen_autoform_champ_type.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'code' => $item[0],
            'libelle' => $item[1],
            'description' => $item[2],
            'usage' => $item[3],
            'example_options' => $item[4],
            'example_texte' => $item[5],
            'example_reponse' => $item[6],

        ];
        $array["unicaen_autoform_champ_type"][] = $instance;
    }
}

fclose($csvFile);

return $array;