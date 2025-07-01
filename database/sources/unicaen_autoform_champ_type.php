<?php

/** Requête utilisée pour la collecte des données **/
//select f.code as formulaire, c.code as categorie, i.code, i.libelle, i.texte, i.ordre, i.element, i.options, i.mots_clefs
//from unicaen_autoform_champ i
//left join unicaen_autoform_categorie c on i.categorie = c.id
//left join unicaen_autoform_formulaire f on c.formulaire = f.id
//where c.histo_destruction IS NULL and f.histo_destruction IS NULL and i.histo_destruction IS NULL
//order by c.code

$csvFile = fopen("./database/sources/csv/unicaen_autoform_champ_type.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'code' => $item[0],
            'libelle' => $item[1],
            'description' => $item[2],
            'usage' => $item[3],
            'example_options' => $item[4],
            'example_texte' => $item[5],
            'example_reponse' => $item[6],

        ];
        $array["unicaen_autoform_champ_type"][] = $instance;
    }
}

fclose($csvFile);

return $array;