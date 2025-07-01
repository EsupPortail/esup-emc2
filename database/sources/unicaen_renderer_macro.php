<?php

/** Requête utilisée pour la collecte des données **/
//select r.code, r.description, r.variable_name, r.methode_name
//from unicaen_renderer_macro r

$csvFile = fopen("./database/sources/csv/unicaen_renderer_macro.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'code' => $item[0],
            'description' => ($item[1] === 'null')?null:$item[1],
            'variable_name' => $item[2],
            'methode_name' => $item[3],
        ];
        $array["unicaen_renderer_macro"][] = $instance;
    }
}

fclose($csvFile);

return $array;