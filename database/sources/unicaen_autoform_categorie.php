<?php

/** Requête utilisée pour la collecte des données **/
//select f.code as formulaire, c.code, c.libelle, c.ordre, c.mots_clefs
//from unicaen_autoform_categorie c
//left join unicaen_autoform_formulaire f on c.formulaire = f.id
//where c.histo_destruction IS NULL and f.histo_destruction IS NULL
//order by c.code

$csvFile = fopen("./database/sources/csv/unicaen_autoform_categorie.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'formulaire' => $item[0],
            'code' => $item[1],
            'libelle' => $item[2],
            'ordre' => $item[3],
            'mots_clefs' => $item[4],
        ];
        $array["unicaen_autoform_categorie"][] = $instance;
    }
}

fclose($csvFile);

return $array;