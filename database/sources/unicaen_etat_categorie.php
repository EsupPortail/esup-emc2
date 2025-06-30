<?php

/** Requête utilisée pour la collecte des données **/
//select c.code, c.libelle, c.icone, c.couleur, c.ordre
//from unicaen_etat_categorie c
//order by c.ordre

$csvFile = fopen("./database/sources/csv/unicaen_etat_categorie.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'code' => $item[0],
            'libelle' => $item[1],
            'icone' => $item[2],
            'couleur' => $item[3],
            'ordre' => $item[4],
        ];
        $array["unicaen_etat_categorie"][] = $instance;
    }
}

fclose($csvFile);

return $array;