<?php

/** Requête utilisée pour la collecte des données **/
//select c.code, p.code, p.libelle, p.ordre
//from unicaen_privilege_privilege p
//join unicaen_privilege_categorie c on p.categorie_id = c.id
//order by c.ordre, p.ordre

$csvFile = fopen("./database/sources/csv/unicaen_privilege_privilege.csv", "r");

$array = [];
$first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'categorie_id' => $item[0],
            'code' => $item[1],
            'libelle' => $item[2],
            'ordre' => $item[3],
        ];
        $array["unicaen_privilege_privilege"][] = $instance;
    }
}

fclose($csvFile);

return $array;

