<?php

/** Requête utilisée pour la collecte des données **/
//select
//u.username, r.role_id
//from unicaen_utilisateur_role_linker l
//join unicaen_utilisateur_user u on l.user_id = u.id
//join unicaen_utilisateur_role r on l.role_id = r.id
//where (u.password = 'application' OR u.username = 'admin')

$data = [
    ["admin", "Administrateur·trice technique"],
    ["metivier", "Administrateur·trice technique"],
];


$array = [];
foreach ($data as $item) {
    $instance = [
        'user_id' => $item[0],
        'role_id' => $item[1],
    ];
    $array["unicaen_utilisateur_role_linker"][] = $instance;
}
return $array;
