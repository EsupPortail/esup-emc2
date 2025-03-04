<?php

namespace EntretienProfessionnel;

use EntretienProfessionnel\Controller\CampagneController;
use EntretienProfessionnel\Controller\CampagneControllerFactory;
use EntretienProfessionnel\Form\Campagne\CampagneForm;
use EntretienProfessionnel\Form\Campagne\CampagneFormFactory;
use EntretienProfessionnel\Form\Campagne\CampagneHydrator;
use EntretienProfessionnel\Form\Campagne\CampagneHydratorFactory;
use EntretienProfessionnel\Provider\Privilege\CampagnePrivileges;
use EntretienProfessionnel\Provider\Privilege\EntretienproPrivileges;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\Campagne\CampagneServiceFactory;
use EntretienProfessionnel\View\Helper\CampagneAvancementViewHelper;
use EntretienProfessionnel\View\Helper\CampagneInformationViewHelper;
use Structure\Provider\Privilege\StructurePrivileges;
use UnicaenPrivilege\Guard\PrivilegeController;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => CampagneController::class,
                    'action' => [
                        'index',
                        'afficher',
                        'extraire',
                        'tester-eligibilite',
                        'notifier-avancement-autorite',
                        'notifier-avancement-superieur',

                        'progression-par-structures',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_AFFICHER,
                    ],
                ],
                [
                    'controller' => CampagneController::class,
                    'action' => [
                        'structure',
                        'structure-progression',
                    ],
                    'privileges' => [
//                        CampagnePrivileges::CAMPAGNE_AFFICHER_STRUCTURE,
                        StructurePrivileges::STRUCTURE_AFFICHER,
                    ],
                ],
                [
                    'controller' => CampagneController::class,
                    'action' => [
                        'superieur',
                        'autorite',
                    ],
                    'privileges' => [
                        EntretienproPrivileges::ENTRETIENPRO_AFFICHER
                    ],
                ],
                [
                    'controller' => CampagneController::class,
                    'action' => [
                        'ajouter',
                        'notifier-ouverture',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_AJOUTER,
                    ],
                ],
                [
                    'controller' => CampagneController::class,
                    'action' => [
                        'modifier',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_MODIFIER,
                    ],
                ],
                [
                    'controller' => CampagneController::class,
                    'action' => [
                        'demander-validation-agent',
                        'demander-validation-superieur',
                        'demander-validation-autorite',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_MODIFIER,
                    ],
                ],
                [
                    'controller' => CampagneController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_HISTORISER,
                    ],
                ],
                [
                    'controller' => CampagneController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => [
                        CampagnePrivileges::CAMPAGNE_DETRUIRE,
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'entretien-professionnel' => [
                'child_routes' => [
                    'campagne' => [
                        'type'  => Literal::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/campagne',
                            'defaults' => [
                                /** @see CampagneController::indexAction() */
                                'controller' => CampagneController::class,
                                'action'     => 'index',
                            ],
                        ],
                        'child_routes' => [
                            'ajouter' => [
                                'type'  => Literal::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/ajouter',
                                    'defaults' => [
                                        /** @see CampagneController::ajouterAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'ajouter',
                                    ],
                                ],
                            ],
                            'notifier-ouverture' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/notifier-ouverture/:campagne',
                                    'defaults' => [
                                        /** @see CampagneController::notifierOuvertureAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'notifier-ouverture',
                                    ],
                                ],
                            ],
                            'afficher' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/afficher/:campagne',
                                    'defaults' => [
                                        /** @see CampagneController::afficherAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'afficher',
                                    ],
                                ],
                            ],
                            'extraire' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/extraire/:campagne',
                                    'defaults' => [
                                        /** @see CampagneController::extraireAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'extraire',
                                    ],
                                ],
                            ],
                            'structure' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/structure/:campagne/:structure',
                                    'defaults' => [
                                        /** @see CampagneController::structureAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'structure',
                                    ],
                                ],
                            ],
                            'structure-progression' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/structure-progression[/:campagne/:structure]',
                                    'defaults' => [
                                        /** @see CampagneController::structureProgressionAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'structure-progression',
                                    ],
                                ],
                            ],
                            'superieur' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/superieur/:campagne[/:agent]',
                                    'defaults' => [
                                        /** @see CampagneController::superieurAction() */
                                        'action'     => 'superieur',
                                    ],
                                ],
                            ],
                            'autorite' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/autorite/:campagne[/:agent]',
                                    'defaults' => [
                                        /** @see CampagneController::autoriteAction() */
                                        'action'     => 'autorite',
                                    ],
                                ],
                            ],
                            'modifier' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/modifier/:campagne',
                                    'defaults' => [
                                        /** @see CampagneController::modifierAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'modifier',
                                    ],
                                ],
                            ],
                            'demander-validation-agent' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/demander-validation-agent/:campagne',
                                    'defaults' => [
                                        /** @see CampagneController::demanderValidationAgentAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'demander-validation-agent',
                                    ],
                                ],
                            ],
                            'demander-validation-superieur' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/demander-validation-superieur/:campagne',
                                    'defaults' => [
                                        /** @see CampagneController::demanderValidationSuperieurAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'demander-validation-superieur',
                                    ],
                                ],
                            ],
                            'demander-validation-autorite' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/demander-validation-autorite/:campagne',
                                    'defaults' => [
                                        /** @see CampagneController::demanderValidationAutoriteAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'demander-validation-autorite',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/historiser/:campagne',
                                    'defaults' => [
                                        /** @see CampagneController::historiserAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/restaurer/:campagne',
                                    'defaults' => [
                                        /** @see CampagneController::restaurerAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'restaurer',
                                    ],
                                ],
                            ],
                            'detruire' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/detruire/:campagne',
                                    'defaults' => [
                                        /** @see CampagneController::detruireAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'detruire',
                                    ],
                                ],
                            ],
                            'tester-eligibilite' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/tester-eligibilite/:campagne/:agent',
                                    'defaults' => [
                                        /** @see CampagneController::testerEligibiliteAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'tester-eligibilite',
                                    ],
                                ],
                            ],
                            'notifier-avancement-autorite' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/notifier-avancement-autorite/:campagne[/:agent]',
                                    'defaults' => [
                                        /** @see CampagneController::notifierAvancementAutoriteAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'notifier-avancement-autorite',
                                    ],
                                ],
                            ],
                            'notifier-avancement-superieur' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/notifier-avancement-superieur/:campagne[/:agent]',
                                    'defaults' => [
                                        /** @see CampagneController::notifierAvancementSuperieurAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'notifier-avancement-superieur',
                                    ],
                                ],
                            ],
                            'progression-par-structures' => [
                                'type'  => Segment::class,
                                'may_terminate' => true,
                                'options' => [
                                    'route'    => '/progression-par-structures/:campagne',
                                    'defaults' => [
                                        /** @see CampagneController::progressionParStructuresAction() */
                                        'controller' => CampagneController::class,
                                        'action'     => 'progression-par-structures',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            CampagneService::class => CampagneServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            CampagneController::class => CampagneControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            CampagneForm::class => CampagneFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            CampagneHydrator::class => CampagneHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'campagneInformation' => CampagneInformationViewHelper::class,
            'campagneAvancement' => CampagneAvancementViewHelper::class,
        ],
    ],

];