<?php
return array(
    'modules' => array(
        'Application',
        'ZfcBase', 'DoctrineModule', 'DoctrineORMModule', 'ZfcUser', 'ZfcUserDoctrineORM', 'BjyAuthorize', 
        'UnicaenApp', 'AssetManager',
        'UnicaenAuth',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
);

