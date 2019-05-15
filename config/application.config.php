<?php

$modules = [
    'ZfcBase', 'DoctrineModule', 'DoctrineORMModule', 'ZfcUser', 'ZfcUserDoctrineORM',
    'BjyAuthorize', 'AssetManager',
    'UnicaenApp', 'UnicaenAuth', 'UnicaenLdap', 'UnicaenDbImport',
    'Autoform',
    'Fichier',
    'Mailing',
    'Octopus',
    'Utilisateur',
    'Application',
];

if ( 'development' == getenv('APPLICATION_ENV') ?: 'production' ) {
    $modules[] = 'ZendDeveloperTools';
    $modules[] = 'UnicaenCode';
}

$moduleListenerOptions = [
    'config_glob_paths'    => [
        'config/autoload/{,*.}{global,local}.php',
    ],
    'module_paths' => [
        './module',
        './vendor',
    ],
];

return [
    'modules' => $modules,
    'module_listener_options' => $moduleListenerOptions,
];