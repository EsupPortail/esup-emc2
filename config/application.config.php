<?php

$modules = [
    'Zend\Cache',
    'Zend\Filter',
    'Zend\Form',
    'Zend\Hydrator',
    'Zend\I18n',
    'Zend\InputFilter',
    'Zend\Log',
    'Zend\Mail',
    'Zend\Mvc\Console',
    'Zend\Mvc\I18n',
    'Zend\Mvc\Plugin\FilePrg',
    'Zend\Mvc\Plugin\FlashMessenger',
    'Zend\Mvc\Plugin\Identity',
    'Zend\Mvc\Plugin\Prg',
    'Zend\Navigation',
    'Zend\Paginator',
    'Zend\Router',
    'Zend\Session',
    'Zend\Validator',

    'DoctrineModule', 'DoctrineORMModule', 'ZfcUser', 'BjyAuthorize' => 'BjyAuthorize',
    'UnicaenApp',
    //'UnicaenAuth',
    'UnicaenLdap', 'UnicaenDbImport',

    'UnicaenPrivilege',
    'UnicaenAuthentification',
    'UnicaenUtilisateurLdapAdapter',
    'Mailing',
    'UnicaenUtilisateur',
    'UnicaenEtat',
    'UnicaenRenderer',
    'UnicaenPdf',
    'UnicaenNote',
    'UnicaenGlossaire',
    'UnicaenParametre',
    'UnicaenValidation',
    'Autoform',
    'Fichier',
    'Indicateur',
    'Formation',
    'EntretienProfessionnel',
    'Metier',
    'Application',
    'ZendDeveloperTools',

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
