<?php

namespace Application;

use Fichier\Controller\FichierController;
use Fichier\Controller\FichierControllerFactory;
use Fichier\Form\Upload\UploadForm;
use Fichier\Form\Upload\UploadFormFactory;
use Fichier\Form\Upload\UploadHydrator;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Fichier\FichierServiceFactory;
use Fichier\Service\Nature\NatureService;
use Fichier\Service\Nature\NatureServiceFactory;
use Fichier\View\Helper\FichierViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FichierController::class,
                    'action' => [
                        'upload',
                        'download',
                        'delete',
                        'historiser',
                        'restaurer',
                        'delete',
                    ],
                    'roles' => [
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
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/upload[/:nature]',
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
                'type'          => Segment::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/upload-fichier[/:nature]',
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
            'historiser-fichier' => [
                'type'          => Segment::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/historiser-fichier/:fichier',
                    'defaults' => [
                        'controller' => FichierController::class,
                        'action'     => 'historiser',
                    ],
                ],
            ],
            'restaurer-fichier' => [
                'type'          => Segment::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/restaurer-fichier/:fichier',
                    'defaults' => [
                        'controller' => FichierController::class,
                        'action'     => 'restaurer',
                    ],
                ],
            ],
            'delete-fichier' => [
                'type'          => Segment::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/delete-fichier/:fichier',
                    'defaults' => [
                        'controller' => FichierController::class,
                        'action'     => 'delete',
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
    ],
    'view_helpers' => [
        'invokables' => [
            'fichier' => FichierViewHelper::class,
        ],
    ],

];