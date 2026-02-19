<?php

namespace Application;

use Application\Assertion\FichePosteAssertion;
use Application\Assertion\FichePosteAssertionFactory;
use Application\Controller\FichePosteController;
use Application\Controller\FichePosteControllerFactory;
use Application\Form\AjouterFicheMetier\AjouterFicheMetierForm;
use Application\Form\AjouterFicheMetier\AjouterFicheMetierFormFactory;
use Application\Form\AjouterFicheMetier\AjouterFicheMetierHydrator;
use Application\Form\AjouterFicheMetier\AjouterFicheMetierHydratorFactory;
use Application\Form\AssocierTitre\AssocierTitreForm;
use Application\Form\AssocierTitre\AssocierTitreFormFactory;
use Application\Form\AssocierTitre\AssocierTitreHydrator;
use Application\Form\AssocierTitre\AssocierTitreHydratorFactory;
use Application\Form\Rifseep\RifseepForm;
use Application\Form\Rifseep\RifseepFormFactory;
use Application\Form\Rifseep\RifseepHydrator;
use Application\Form\Rifseep\RifseepHydratorFactory;
use Application\Form\SpecificitePoste\SpecificitePosteForm;
use Application\Form\SpecificitePoste\SpecificitePosteFormFactory;
use Application\Form\SpecificitePoste\SpecificitePosteHydrator;
use Application\Provider\Privilege\FichePostePrivileges;
use Application\Service\ApplicationsRetirees\ApplicationsRetireesService;
use Application\Service\ApplicationsRetirees\ApplicationsRetireesServiceFactory;
use Application\Service\CompetencesRetirees\CompetencesRetireesService;
use Application\Service\CompetencesRetirees\CompetencesRetireesServiceFactory;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\FichePoste\FichePosteServiceFactory;
use Application\Service\SpecificitePoste\SpecificitePosteService;
use Application\Service\SpecificitePoste\SpecificitePosteServiceFactory;
use Application\View\Helper\FicheMetierExterneViewHelper;
use Application\View\Helper\FichePosteGraphViewHelper;
use Application\View\Helper\FichesPostesAsArrayViewHelperFactory;
use Application\View\Helper\RaisonsViewHelper;
use Application\View\Helper\SpecificitePosteViewHelper;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'FichePoste' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            FichePostePrivileges::FICHEPOSTE_INDEX,
                            FichePostePrivileges::FICHEPOSTE_AFFICHER,
                            FichePostePrivileges::FICHEPOSTE_AJOUTER,
                            FichePostePrivileges::FICHEPOSTE_MODIFIER,
                            FichePostePrivileges::FICHEPOSTE_HISTORISER,
                            FichePostePrivileges::FICHEPOSTE_DETRUIRE,
                            FichePostePrivileges::FICHEPOSTE_ETAT,
                            FichePostePrivileges::FICHEPOSTE_VALIDER_RESPONSABLE,
                            FichePostePrivileges::FICHEPOSTE_VALIDER_AGENT,
                        ],
                        'resources' => ['FichePoste'],
                        'assertion' => FichePosteAssertion::class
                    ],
                ],
            ],
        ],
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'action',
                    ],
                    'role' => 'user',
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'index',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_INDEX,
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'afficher',
                        'export',
                        'exporter',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_AFFICHER,
                    'assertion' => FichePosteAssertion::class,
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'ajouter',
                        'dupliquer',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_AJOUTER,
                    'assertion' => FichePosteAssertion::class,
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'modifier-information-poste',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_MODIFIER_POSTE,
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'editer',

                        'associer-agent',
                        'associer-titre',
                        'editer-rifseep',
                        'editer-specificite',
                        'ajouter-fiche-metier',
                        'retirer-fiche-metier',
                        'modifier-fiche-metier',
                        'modifier-repartition',
                        'selectionner-activite',
                        'selectionner-mission',

                        'selectionner-applications-retirees',
                        'selectionner-competences-retirees',
                        'selectionner-formations-retirees',

                        'modifier-code-fonction',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_MODIFIER,
                    'assertion' => FichePosteAssertion::class,
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'changer-etat',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_ETAT,
                    'assertion' => FichePosteAssertion::class,
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'valider',
                        'revoquer',
                    ],
                    'privileges' => [
                        FichePostePrivileges::FICHEPOSTE_VALIDER_AGENT,
                        FichePostePrivileges::FICHEPOSTE_VALIDER_RESPONSABLE,
                    ],
                    'assertion' => FichePosteAssertion::class,
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_HISTORISER,
                    'assertion' => FichePosteAssertion::class,
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_DETRUIRE,
                    'assertion' => FichePosteAssertion::class,
                ],
            ],
        ],
    ],

    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'fiche-poste' => [
                                'label' => 'Fiches de poste',
                                'route' => 'fiche-poste',
                                'resource' => FichePostePrivileges::getResourceId(FichePostePrivileges::FICHEPOSTE_INDEX),
                                'order' => 2049,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'fiche-poste' => [
                'type' => Literal::class,
                'may_terminate' => true,
                'options' => [
                    'route' => '/fiche-poste',
                    'defaults' => [
                        'controller' => FichePosteController::class,
                        'action' => 'index',
                    ],
                ],
                'child_routes' => [
                    'ajouter' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/ajouter[/:agent]',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'ajouter',
                            ],
                        ],
                    ],
                    'dupliquer' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/dupliquer/:structure/:agent',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'dupliquer',
                            ],
                        ],
                    ],
                    'afficher' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/afficher/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'afficher',
                            ],
                        ],
                    ],
                    'action' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/action/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'action',
                            ],
                        ],
                    ],
                    'valider' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/valider/:fiche-poste/:type',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'valider',
                            ],
                        ],
                    ],
                    'revoquer' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/revoquer/:fiche-poste/:validation',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'revoquer',
                            ],
                        ],
                    ],
                    'export' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/export/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'export',
                            ],
                        ],
                    ],
                    'exporter' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/exporter/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'exporter',
                            ],
                        ],
                    ],
                    'changer-etat' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/changer-etat/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'changer-etat',
                            ],
                        ],
                    ],
                    'editer' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/editer[/:fiche-poste]',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'editer',
                            ],
                        ],
                    ],
                    'historiser' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/historiser/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'historiser',
                            ],
                        ],
                    ],
                    'restaurer' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/restaurer/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'restaurer',
                            ],
                        ],
                    ],
                    'detruire' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/detruire/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'detruire',
                            ],
                        ],
                    ],
                    'associer-agent' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/associer-agent/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'associer-agent',
                            ],
                        ],
                    ],
                    'associer-titre' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/associer-titre/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'associer-titre',
                            ],
                        ],
                    ],
                    /** @see FichePosteController::modifierInformationPosteAction() */
                    'modifier-information-poste' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/modifier-information-poste/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'modifier-information-poste',
                            ],
                        ],
                    ],
                    'modifier-code-fonction' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/modifier-code-fonction/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'modifier-code-fonction',
                            ],
                        ],
                    ],
                    'ajouter-fiche-metier' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/ajouter-fiche-metier/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'ajouter-fiche-metier',
                            ],
                        ],
                    ],
                    'modifier-fiche-metier' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/modifier-fiche-metier/:fiche-poste/:fiche-type-externe',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'modifier-fiche-metier',
                            ],
                        ],
                    ],
                    'modifier-repartition' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/modifier-repartition/:fiche-poste/:fiche-type',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'modifier-repartition',
                            ],
                        ],
                    ],
                    'retirer-fiche-metier' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/retirer-fiche-metier/:fiche-poste/:fiche-type-externe',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'retirer-fiche-metier',
                            ],
                        ],
                    ],
                    'selectionner-activite' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/selectionner-activite/:fiche-poste/:fiche-type-externe',
                            'defaults' => [
                                /** @see FichePosteController::selectionnerActiviteAction() */
                                'action' => 'selectionner-activite',
                            ],
                        ],
                    ],
                    'selectionner-mission' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/selectionner-mission/:fiche-poste/:fiche-type-externe',
                            'defaults' => [
                                /** @see FichePosteController::selectionnerMissionAction() */
                                'action' => 'selectionner-mission',
                            ],
                        ],
                    ],
                    'editer-rifseep' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/editer-rifseep/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'editer-rifseep',
                            ],
                        ],
                    ],
                    'editer-specificite' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/editer-specificite/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'editer-specificite',
                            ],
                        ],
                    ],
                    'selectionner-applications-retirees' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/selectionner-applications-retirees/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'selectionner-applications-retirees',
                            ],
                        ],
                    ],
                    'selectionner-competences-retirees' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/selectionner-competences-retirees/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'selectionner-competences-retirees',
                            ],
                        ],
                    ],
                    'selectionner-formations-retirees' => [
                        'type' => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route' => '/selectionner-formations-retirees/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action' => 'selectionner-formations-retirees',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            FichePosteAssertion::class => FichePosteAssertionFactory::class,

            ApplicationsRetireesService::class => ApplicationsRetireesServiceFactory::class,
            CompetencesRetireesService::class => CompetencesRetireesServiceFactory::class,
            FichePosteService::class => FichePosteServiceFactory::class,
            SpecificitePosteService::class => SpecificitePosteServiceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            FichePosteController::class => FichePosteControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            AjouterFicheMetierForm::class => AjouterFicheMetierFormFactory::class,
            AssocierTitreForm::class => AssocierTitreFormFactory::class,
            SpecificitePosteForm::class => SpecificitePosteFormFactory::class,
            RifseepForm::class => RifseepFormFactory::class,
        ],
    ],
    'hydrators' => [
        'invokables' => [
            SpecificitePosteHydrator::class => SpecificitePosteHydrator::class,
        ],
        'factories' => [
            AjouterFicheMetierHydrator::class => AjouterFicheMetierHydratorFactory::class,
            AssocierTitreHydrator::class => AssocierTitreHydratorFactory::class,
            RifseepHydrator::class => RifseepHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'specificitePoste' => SpecificitePosteViewHelper::class,
            'ficheMetierExterne' => FicheMetierExterneViewHelper::class,
            'raisons' => RaisonsViewHelper::class,
            'fichePosteGraph' => FichePosteGraphViewHelper::class,
        ],
        'factories' => [
            'fichesPostesAsArray' => FichesPostesAsArrayViewHelperFactory::class,
        ]
    ],

];