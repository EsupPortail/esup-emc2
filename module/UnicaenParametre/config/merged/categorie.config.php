<?php

namespace UnicaenParametre;

use UnicaenParametre\Controller\CategorieController;
use UnicaenParametre\Controller\CategorieControllerFactory;
use UnicaenParametre\Form\Categorie\CategorieForm;
use UnicaenParametre\Form\Categorie\CategorieFormFactory;
use UnicaenParametre\Form\Categorie\CategorieHydrator;
use UnicaenParametre\Form\Categorie\CategorieHydratorFactory;
use UnicaenParametre\Service\Categorie\CategorieService;
use UnicaenParametre\Service\Categorie\CategorieServiceFactory;
use UnicaenPrivilege\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [

            ],
        ],
    ],

    'router'          => [
        'routes' => [
        ],
    ],

    'service_manager' => [
        'factories' => [
            CategorieService::class => CategorieServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            CategorieController::class => CategorieControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CategorieForm::class => CategorieFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CategorieHydrator::class => CategorieHydratorFactory::class,
        ],
    ]

];