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
    'UnicaenEnquete',
    'UnicaenIndicateur',
    'Unicaen\BddAdmin',
    'Unicaen\Console',
    'UnicaenSynchro',
    'UnicaenObservation',

    'MissionSpecifique',
    'Structure',
    'EntretienProfessionnel',
    'Carriere',
    'Metier',
    'FicheReferentiel',
    'FicheMetier',
    'FichePoste',

    'Fichier',
    'Element',
    'Application',

    'Laminas\DeveloperTools',

];

$moduleListenerOptions = [
    'config_glob_paths'    => [
        'config/autoload/{,*.}{local,global}.php',
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
