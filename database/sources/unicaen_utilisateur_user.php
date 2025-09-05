<?php

/** Requête utilisée pour la collecte des données **/
//select
//u.username, u.display_name, u.email, u.password,
//case (u.state) when true then 't' else 'f' end
//from unicaen_utilisateur_user u
//where password <> 'ldap'

$data = [
    ["EMC2","Emploi Mobilité Compétence Carrière",null,"local","false", 0],
    ["admin","Local Adminstrateur","local-admin@univ-demo.fr","\$2y$14\$D.o5.K1hKlL2ZbhVL3M2su9RnscuxgsA01fkPowTcs0vpulcJE1o2","t"],
    ["metivier","Jean-Philippe METIVIER","jean-philippe.metivier@unicaen.fr","ldap","t"],
    ["houillier","Fanny HOUILLIER","fanny.houillier@unicaen.fr","ldap","t"],
    ["cauchon251","Marine CAUCHON","marine.cauchon@unicaen.fr","ldap","t"],
    ["rocher202","Fabien ROCHER","fabien.rocher@unicaen.fr","ldap","t"],
    ["grimpar221","Aurelien GRIMPARD","aurelien.grimpard@unicaen.fr","ldap","t"],
];

$array = [];
foreach ($data as $item) {
    $instance = [
        'username' => $item[0],
        'display_name' => $item[1],
        'email' => $item[2],
        'password' => $item[3],
        'state' => $item[4],
    ];
    if (isset($item[5])) { $instance['id'] = $item[5]; }
    $array["unicaen_utilisateur_user"][] = $instance;
}
return $array;
