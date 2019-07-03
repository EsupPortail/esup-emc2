<?php

$env = getenv('APPLICATION_ENV') ?: 'production';

return [
    'translator' => [
        'locale' => 'fr_FR',
    ],

    'module_listener_options' => [
        'config_cache_enabled'     => ($env === 'production'),
        'config_cache_key'         => 'app_config',
        'module_map_cache_enabled' => ($env === 'production'),
        'module_map_cache_key'     => 'module_map',
        'cache_dir'                => 'data/config/',
        'check_dependencies'       => ($env !== 'production'),
    ],

];
