<?php

use Unicaen\BddAdmin\Bdd;
use Doctrine\DBAL\Driver\PDO\PgSQL\Driver;
use Doctrine\DBAL\Driver\PgSQL\Driver as DriverBis;


$config = [
    'bdd-admin' => [
        'bdd'                              => [
            'driver'   => (DB_DRIVER === Driver::class OR DB_DRIVER === DriverBis::class)?'Postgresql':'Oracle',
            'host'     => DB_HOSTNAME,
            'port'     => DB_PORT,
            'dbname'   => DB_NAME,
            'username' => DB_USERNAME,
            'password' => DB_PASSWORD,
        ],

        /** cela eventuellement en local ... */
        /* Facultatif, permet de spécifier une fois pour toutes le répertoire où sera renseignée la DDL de votre BDD */
        Bdd::OPTION_DDL_DIR                => getcwd() . '/database/ddl',
        /* Facultatif, spécifie le répertoire où seront stockés vos scripts de migration si vous en avez */
        Bdd::OPTION_MIGRATION_DIR          => getcwd() . '/database/migration/',
        /* Facultatif, permet de personnaliser l'ordonnancement des colonnes dans les tables */
        Bdd::OPTION_COLUMNS_POSITIONS_FILE => getcwd() . '/database/ddl_columns_pos.php',
    ],
];


return $config;