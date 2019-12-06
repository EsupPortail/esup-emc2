<?php

namespace Application;

use Application\Controller\FichePosteController;
use Application\Controller\FichePosteControllerFactory;
use Application\Form\AjouterFicheMetier\AjouterFicheMetierForm;
use Application\Form\AjouterFicheMetier\AjouterFicheMetierFormFactory;
use Application\Form\AjouterFicheMetier\AjouterFicheMetierHydrator;
use Application\Form\AjouterFicheMetier\AjouterFicheMetierHydratorFactory;
use Application\Form\AssocierAgent\AssocierAgentForm;
use Application\Form\AssocierAgent\AssocierAgentFormFactory;
use Application\Form\AssocierAgent\AssocierAgentHydrator;
use Application\Form\AssocierAgent\AssocierAgentHydratorFactory;
use Application\Form\AssocierPoste\AssocierPosteForm;
use Application\Form\AssocierPoste\AssocierPosteFormFactory;
use Application\Form\AssocierPoste\AssocierPosteHydrator;
use Application\Form\AssocierPoste\AssocierPosteHydratorFactory;
use Application\Form\AssocierTitre\AssocierTitreForm;
use Application\Form\AssocierTitre\AssocierTitreFormFactory;
use Application\Form\AssocierTitre\AssocierTitreHydrator;
use Application\Form\AssocierTitre\AssocierTitreHydratorFactory;
use Application\Form\FichePosteCreation\FichePosteCreationForm;
use Application\Form\FichePosteCreation\FichePosteCreationFormFactory;
use Application\Form\FichePosteCreation\FichePosteCreationHydrator;
use Application\Form\FichePosteCreation\FichePosteCreationHydratorFactory;
use Application\Form\SpecificitePoste\SpecificitePosteForm;
use Application\Form\SpecificitePoste\SpecificitePosteFormFactory;
use Application\Form\SpecificitePoste\SpecificitePosteHydrator;
use Application\Service\ApplicationsConservees\ApplicationConserveesServiceFactory;
use Application\Service\ApplicationsConservees\ApplicationsConserveesService;
use Application\Service\CompetencesConservees\CompetencesConserveesService;
use Application\Service\CompetencesConservees\CompetencesConserveesServiceFactory;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\FichePoste\FichePosteServiceFactory;
use Application\Service\FormationsConservees\FormationsConserveesService;
use Application\Service\FormationsConservees\FormationsConserveesServiceFactory;
use UnicaenAuth\Guard\PrivilegeController;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'bjyauthorize' => [
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'index',
                        'ajouter',
                        'afficher',
                        'editer',
                        'historiser',
                        'restaurer',
                        'detruire',
                        'associer-agent',
                        'associer-poste',
                        'associer-titre',
                        'editer-specificite',
                        'ajouter-fiche-metier',
                        'retirer-fiche-metier',
                        'modifier-fiche-metier',
                        'selectionner-activite',
                        'export',

                        'selectionner-applications-conservees',
                        'selectionner-competences-conservees',
                        'selectionner-formations-conservees',
                        'test-affichage-application-bloc',
                    ],
                    'roles' => [
                    ],
                ],
            ],
        ],
    ],

    'router'          => [
        'routes' => [
            'fiche-poste' => [
                'type'  => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route'    => '/fiche-poste',
                    'defaults' => [
                        'controller' => FichePosteController::class,
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
                                'controller' => FichePosteController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/afficher/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'afficher',
                            ],
                        ],
                    ],
                    'export' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/export/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'export',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/editer[/:fiche-poste]',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'editer',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/historiser/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/restaurer/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/detruire/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'detruire',
                            ],
                        ],
                    ],
                    'associer-agent' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/associer-agent/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'associer-agent',
                            ],
                        ],
                    ],
                    'associer-poste' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/associer-poste/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'associer-poste',
                            ],
                        ],
                    ],
                    'associer-titre' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/associer-titre/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'associer-titre',
                            ],
                        ],
                    ],
                    'ajouter-fiche-metier' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/ajouter-fiche-metier/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'ajouter-fiche-metier',
                            ],
                        ],
                    ],
                    'modifier-fiche-metier' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/modifier-fiche-metier/:fiche-poste/:fiche-type-externe',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'modifier-fiche-metier',
                            ],
                        ],
                    ],
                    'retirer-fiche-metier' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/retirer-fiche-metier/:fiche-poste/:fiche-type-externe',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'retirer-fiche-metier',
                            ],
                        ],
                    ],
                    'selectionner-activite' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/selectionner-activite/:fiche-poste/:fiche-type-externe',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'selectionner-activite',
                            ],
                        ],
                    ],
                    'editer-specificite' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/editer-specificite/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'editer-specificite',
                            ],
                        ],
                    ],
                    'selectionner-applications-conservees' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/selectionner-applications-conservees/:fiche-poste/:fiche-metier',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'selectionner-applications-conservees',
                            ],
                        ],
                    ],
                    'test-affichage-application-bloc' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/test-affichage-application-bloc/:fiche-poste/:fiche-metier',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'test-affichage-application-bloc',
                            ],
                        ],
                    ],
                    'selectionner-competences-conservees' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/selectionner-competences-conservees/:fiche-poste/:fiche-metier',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'selectionner-competences-conservees',
                            ],
                        ],
                    ],
                    'selectionner-formations-conservees' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/selectionner-formations-conservees/:fiche-poste/:fiche-metier',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'selectionner-formations-conservees',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FichePosteService::class => FichePosteServiceFactory::class,
            ApplicationsConserveesService::class => ApplicationConserveesServiceFactory::class,
            CompetencesConserveesService::class => CompetencesConserveesServiceFactory::class,
            FormationsConserveesService::class => FormationsConserveesServiceFactory::class,
        ],
    ],
    'controllers'     => [
        'factories' => [
            FichePosteController::class => FichePosteControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AjouterFicheMetierForm::class => AjouterFicheMetierFormFactory::class,
            AssocierAgentForm::class => AssocierAgentFormFactory::class,
            AssocierPosteForm::class => AssocierPosteFormFactory::class,
            AssocierTitreForm::class => AssocierTitreFormFactory::class,
            FichePosteCreationForm::class => FichePosteCreationFormFactory::class,
            SpecificitePosteForm::class => SpecificitePosteFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            SpecificitePosteHydrator::class => SpecificitePosteHydrator::class,
        ],
        'factories' => [
            AjouterFicheMetierHydrator::class => AjouterFicheMetierHydratorFactory::class,
            AssocierAgentHydrator::class => AssocierAgentHydratorFactory::class,
            AssocierPosteHydrator::class => AssocierPosteHydratorFactory::class,
            AssocierTitreHydrator::class => AssocierTitreHydratorFactory::class,
            FichePosteCreationHydrator::class => FichePosteCreationHydratorFactory::class,
        ],
    ]

];