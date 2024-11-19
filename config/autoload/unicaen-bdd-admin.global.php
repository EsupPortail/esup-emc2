<?php

use Unicaen\BddAdmin\Bdd;


$config = [
    'bdd-admin' => [

        /** cela eventuellement en local ... */
        /* Facultatif, permet de spécifier une fois pour toutes le répertoire où sera renseignée la DDL de votre BDD */
        Bdd::OPTION_DDL_DIR => getcwd() . '/database/ddl',
        /* Facultatif, spécifie le répertoire où seront stockés vos scripts de migration si vous en avez */
        Bdd::OPTION_MIGRATION_DIR => getcwd() . '/database/migration/',
        /* Facultatif, permet de personnaliser l'ordonnancement des colonnes dans les tables */
        Bdd::OPTION_COLUMNS_POSITIONS_FILE => getcwd() . '/database/ddl_columns_pos.php',
    ],
];


return $config;