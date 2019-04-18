<?php

namespace Application;

use Autoform\Provider\Privilege\IndexPrivileges;
use Fichier\Controller\Fichier\FichierController;
use Fichier\Controller\Fichier\FichierControllerFactory;
use Fichier\Form\Upload\UploadForm;
use Fichier\Form\Upload\UploadFormFactory;
use Fichier\Form\Upload\UploadHydrator;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Fichier\FichierServiceFactory;
use Fichier\Service\Nature\NatureService;
use Fichier\Service\Nature\NatureServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Mvc\Router\Http\Literal;
use Zend\Mvc\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FichierController::class,
                    'action' => [
                        'upload',
                        'download',
                    ],
                    'roles' => [
                        'Administrateur technique'
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'fichier' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/fichier',
                ],
                'child_routes' => [
                    'upload' => [
                        'type' => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/upload',
                            'default' => [
                                'controller' => FichierController::class,
                                'action' => 'upload',
                            ],
                        ],
                        'child_routes' => [

                        ],
                    ],
                ],
            ],
            'upload-fichier' => [
                'type'          => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/upload-fichier',
                    'defaults' => [
                        'controller' => FichierController::class,
                        'action'     => 'upload',
                    ],
                ],
            ],
            'download-fichier' => [
                'type'          => Segment::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/download-fichier/:fichier',
                    'defaults' => [
                        'controller' => FichierController::class,
                        'action'     => 'download',
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FichierService::class => FichierServiceFactory::class,
            NatureService::class => NatureServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FichierController::class => FichierControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            UploadForm::class => UploadFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            UploadHydrator::class => UploadHydrator::class,
        ],
    ]

];