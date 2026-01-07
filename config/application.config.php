<?php

$modules = [
    'Laminas\Cache',
    'Laminas\Filter',
    'Laminas\Form',
    'Laminas\Hydrator',
    'Laminas\I18n',
    'Laminas\InputFilter',
    'Laminas\Log',
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
    'UnicaenIndicateur',
    'Unicaen\BddAdmin',
    'UnicaenSynchro',
    'UnicaenObservation',
    'UnicaenContact',
    'UnicaenFichier',
    'UnicaenStorage',

    'Referentiel',
    'Agent',
    'MissionSpecifique',
    'Structure',
    'EntretienProfessionnel',
    'Carriere',
    'Metier',
    'FicheMetier',
    'FichePoste',

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
