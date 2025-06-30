<?php

/** Requête utilisée pour la collecte des données **/
//select c.code, c.libelle, c.description, c.ordre
//from unicaen_parametre_categorie c
//order by c.ordre

$csvFile = fopen("./database/sources/csv/unicaen_parametre_categorie.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'code' => $item[0],
            'libelle' => $item[1],
            'description' => $item[2],
            'ordre' => $item[3],
        ];
        $array["unicaen_parametre_categorie"][] = $instance;
    }
}

fclose($csvFile);

return $array;

