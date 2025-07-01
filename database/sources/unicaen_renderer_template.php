<?php

/** Requête utilisée pour la collecte des données **/
//select r.code, r.description, r.document_type, r.document_sujet, r.document_corps, r.document_css, r.namespace
//from unicaen_renderer_template r

$csvFile = fopen("./database/sources/csv/unicaen_renderer_template.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'code' => $item[0],
            'description' => ($item[1] === 'null')?null:$item[1],
            'document_type' => $item[2],
            'document_sujet' => $item[3],
            'document_corps' => $item[4],
            'document_css' => ($item[5] === 'null')?null:$item[5],
            'namespace' => ($item[6] === 'null')?null:$item[6],
        ];
        $array["unicaen_renderer_template"][] = $instance;
    }
}

fclose($csvFile);

return $array;