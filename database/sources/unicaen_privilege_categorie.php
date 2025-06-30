<?php

/** Requête utilisée pour la collecte des données **/
//select c.code, c.libelle, c.namespace, c.ordre
//from unicaen_privilege_categorie c
//order by c.ordre

$csvFile = fopen("./database/sources/csv/unicaen_privilege_categorie.csv", "r");

$array = [];
$first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'code' => $item[0],
            'libelle' => $item[1],
            'namespace' => $item[2],
            'ordre' => $item[3],
        ];
        $array["unicaen_privilege_categorie"][] = $instance;
    }
}

fclose($csvFile);

return $array;