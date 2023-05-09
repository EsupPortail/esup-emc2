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
    'UnicaenParametre',
    'UnicaenValidation',
    'UnicaenEvenement',
    'UnicaenAide',
    'UnicaenIndicateur',
    'Referentiel',

    'Structure',
    'EntretienProfessionnel',
    'Carriere',
    'Metier',
    'FicheMetier',
    'FichePoste',

    'Formation',
    'Fichier',
    'Element',
    'Application',

    'Laminas\DeveloperTools',
//    'UnicaenTest',

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
