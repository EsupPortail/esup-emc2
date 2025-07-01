<?php

/** Requête utilisée pour la collecte des données **/
//select c.code, p.code, p.libelle, p.description, p.valeurs_possibles, p.ordre
//from unicaen_parametre_parametre p
//join unicaen_parametre_categorie c on p.categorie_id = c.id
//order by c.ordre, p.ordre

$csvFile = fopen("./database/sources/csv/unicaen_parametre_parametre.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'categorie_id' => $item[0],
            'code' => $item[1],
            'libelle' => $item[2],
            'description' => $item[3],
            'valeurs_possibles' => $item[4],
            'ordre' => $item[5],
        ];
        if (isset($item[6])) { $instance['valeur'] = $item[6]; }
        $array["unicaen_parametre_parametre"][] = $instance;
    }
}

fclose($csvFile);

return $array;