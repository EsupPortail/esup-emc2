<?php

$modules = [
    'Laminas\Cache',
    'Laminas\Filter',
    'Laminas\Form',
    'Laminas\Hydrator',
    'Laminas\I18n',
    'Laminas\InputFilter',
    'Laminas\Log',
    'Laminas\Mail',
    'Laminas\Mvc\Console',
    'Laminas\Mvc\I18n',
    'Laminas\Mvc\Plugin\FilePrg',
    'Laminas\Mvc\Plugin\FlashMessenger',
    'Laminas\Mvc\Plugin\Identity',
    'Laminas\Mvc\Plugin\Prg',
    'Laminas\Navigation',
    'Laminas\Paginator',
    'Laminas\Router',
    'Laminas\Session',
    'Laminas\Validator',

    'DoctrineModule', 'DoctrineORMModule', 'ZfcUser', 'BjyAuthorize' => 'BjyAuthorize',
    'UnicaenApp',
    'UnicaenLdap',
    'UnicaenDbImport',
    'Octopus',

    'UnicaenPrivilege',
    'UnicaenAuthentification',
    'UnicaenUtilisateurLdapAdapter',
    'UnicaenUtilisateurOctopusAdapter',
    'UnicaenUtilisateur',
    'UnicaenAutoform',
    'UnicaenEtat',
    'UnicaenRenderer',
    'UnicaenPdf',
    'UnicaenMail',
    'UnicaenGlossaire',
    'UnicaenParametre',
    'UnicaenValidation',
    'UnicaenEvenement',

    'Indicateur',
    'EntretienProfessionnel',
    'Carriere',
    'Metier',
    'Formation',
    'Structure',
    'Fichier',
    'Element',
    'Application',

//    'LaminasDeveloperTools',

];

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
