<?php

/** Requête utilisée pour la collecte des données **/
//select f.code as formulaire, c.code as categorie, i.code, i.libelle, i.texte, i.ordre, i.element, i.options, i.mots_clefs
//from unicaen_autoform_champ i
//left join unicaen_autoform_categorie c on i.categorie = c.id
//left join unicaen_autoform_formulaire f on c.formulaire = f.id
//where c.histo_destruction IS NULL and f.histo_destruction IS NULL and i.histo_destruction IS NULL
//order by c.code

$csvFile = fopen("./database/sources/csv/unicaen_autoform_champ.csv", "r");

$array = []; $first = true;
while (($item = fgetcsv($csvFile)) !== FALSE) {
    if ($first) $first = false;
    else {
        $instance = [
            'formulaire' => $item[0],
            'categorie' => $item[1],
            'code' => $item[2],
            'libelle' => $item[3],
            'texte' => $item[4],
            'ordre' => $item[5],
            'element' => $item[6],
            'options' => $item[7],
            'mots_clefs' => $item[8],
        ];
        $array["unicaen_autoform_champ"][] = $instance;
    }
}

fclose($csvFile);

return $array;