<?php

/** Requête utilisée pour la collecte des données **/
//select upc.code as categorie_id, upp.code as privilege_id, uur.role_id as role_id
//from unicaen_privilege_privilege_role_linker l
//join unicaen_privilege_privilege upp on l.privilege_id = upp.id
//join unicaen_privilege_categorie upc on upc.id = upp.categorie_id
//join unicaen_utilisateur_role uur on l.role_id = uur.id

$csvFile = fopen("./database/sources/csv/unicaen_privilege_privilege_role_linker.csv", "r");

$array = [];
$first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'categorie_id' => $item[0],
            'privilege_id' => $item[1],
            'role_id' => $item[2],
        ];
        $array["unicaen_privilege_privilege_role_linker"][] = $instance;
    }
}

fclose($csvFile);

return $array;
