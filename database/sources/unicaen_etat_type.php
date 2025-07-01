<?php

/** Requête utilisée pour la collecte des données **/
//select c.code, t.code, t.libelle, t.icone, t.couleur, t.ordre
//from unicaen_etat_type t
//join unicaen_etat_categorie c on t.categorie_id = c.id
//order by c.ordre

$csvFile = fopen("./database/sources/csv/unicaen_etat_type.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'categorie_id' => $item[0],
            'code' => $item[1],
            'libelle' => $item[2],
            'icone' => $item[3],
            'couleur' => $item[4],
            'ordre' => $item[5],
        ];
        $array["unicaen_etat_type"][] = $instance;
    }
}

fclose($csvFile);

return $array;