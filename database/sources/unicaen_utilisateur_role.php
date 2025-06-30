<?php

/** Requête utilisée pour la collecte des données **/
//select r.role_id, r.libelle,
//case (r.is_auto) when true then 't' else 'f' end,
//r.description
//from unicaen_utilisateur_role r
$csvFile = fopen("./database/sources/csv/unicaen_utilisateur_role.csv", "r");

$array = [];
$first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'role_id' => $item[0],
            'libelle' => $item[1],
            'is_auto' => $item[2],
            'description' => $item[3],
        ];
        $array["unicaen_utilisateur_role"][] = $instance;
    }
}

fclose($csvFile);

return $array;
