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
use Application\Form\Expertise\ExpertiseForm;
use Application\Form\Expertise\ExpertiseFormFactory;
use Application\Form\Expertise\ExpertiseHydrator;
use Application\Form\Expertise\ExpertiseHydratorFactory;
use Application\Form\SpecificitePoste\SpecificitePosteForm;
use Application\Form\SpecificitePoste\SpecificitePosteFormFactory;
use Application\Form\SpecificitePoste\SpecificitePosteHydrator;
use Application\Provider\Privilege\FichePostePrivileges;
use Application\Service\ActivitesDescriptionsRetirees\ActivitesDescriptionsRetireesService;
use Application\Service\ActivitesDescriptionsRetirees\ActivitesDescriptionsRetireesServiceFactory;
use Application\Service\ApplicationsRetirees\ApplicationsRetireesService;
use Application\Service\ApplicationsRetirees\ApplicationsRetireesServiceFactory;
use Application\Service\CompetencesRetirees\CompetencesRetireesService;
use Application\Service\CompetencesRetirees\CompetencesRetireesServiceFactory;
use Application\Service\Expertise\ExpertiseService;
use Application\Service\Expertise\ExpertiseServiceFactory;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\FichePoste\FichePosteServiceFactory;
use Application\Service\FormationsRetirees\FormationsRetireesService;
use Application\Service\FormationsRetirees\FormationsRetireesServiceFactory;
use Application\Service\SpecificitePoste\SpecificitePosteService;
use Application\Service\SpecificitePoste\SpecificitePosteServiceFactory;
use Application\View\Helper\FichePosteGraphViewHelper;
use Application\View\Helper\FichesPostesAsArrayViewHelper;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

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
                            FichePostePrivileges::FICHEPOSTE_MODIFIER,
                            FichePostePrivileges::FICHEPOSTE_HISTORISER,
                            FichePostePrivileges::FICHEPOSTE_DETRUIRE,
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
                        'index',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_INDEX,
                    'assertion'  => FichePosteAssertion::class,
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'afficher',
                        'export',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_AFFICHER,
                    'assertion'  => FichePosteAssertion::class,
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'ajouter',
                        'dupliquer',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_AJOUTER,
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'editer',

                        'associer-agent',
                        'associer-poste',
                        'associer-titre',
                        'editer-specificite',
                        'ajouter-fiche-metier',
                        'retirer-fiche-metier',
                        'modifier-fiche-metier',
                        'modifier-repartition',
                        'selectionner-activite',

                        'selectionner-applications-retirees',
                        'selectionner-competences-retirees',
                        'selectionner-formations-retirees',
                        'selectionner-descriptions-retirees',

                        'ajouter-expertise',
                        'modifier-expertise',
                        'historiser-expertise',
                        'restaurer-expertise',
                        'supprimer-expertise',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_MODIFIER,
                    'assertion'  => FichePosteAssertion::class,
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'historiser',
                        'restaurer',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_HISTORISER,
                    'assertion'  => FichePosteAssertion::class,
                ],
                [
                    'controller' => FichePosteController::class,
                    'action' => [
                        'detruire',
                    ],
                    'privileges' => FichePostePrivileges::FICHEPOSTE_DETRUIRE,
                    'assertion'  => FichePosteAssertion::class,
                ],
            ],
        ],
    ],

    'navigation'      => [
        'default' => [
            'home' => [
                'pages' => [
                    'gestion' => [
                        'pages' => [
                            'fiche-poste' => [
                                'label' => 'Fiches de poste',
                                'route' => 'fiche-poste',
                                'resource' =>  FichePostePrivileges::getResourceId(FichePostePrivileges::FICHEPOSTE_INDEX) ,
                                'order'    => 1000,
                            ],
                        ],
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
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/ajouter[/:agent]',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'ajouter',
                            ],
                        ],
                    ],
                    'dupliquer' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/dupliquer/:structure/:agent',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'dupliquer',
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
                    'modifier-repartition' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/modifier-repartition/:fiche-poste/:fiche-type',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'modifier-repartition',
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
                    'selectionner-applications-retirees' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/selectionner-applications-retirees/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'selectionner-applications-retirees',
                            ],
                        ],
                    ],
                    'selectionner-competences-retirees' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/selectionner-competences-retirees/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'selectionner-competences-retirees',
                            ],
                        ],
                    ],
                    'selectionner-formations-retirees' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/selectionner-formations-retirees/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'selectionner-formations-retirees',
                            ],
                        ],
                    ],
                    'selectionner-descriptions-retirees' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/selectionner-descriptions-retirees/:fiche-poste/:fiche-metier/:activite',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'selectionner-descriptions-retirees',
                            ],
                        ],
                    ],
                    'ajouter-expertise' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/ajouter-expertise/:fiche-poste',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'ajouter-expertise',
                            ],
                        ],
                    ],
                    'modifier-expertise' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/modifier-expertise/:expertise',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'modifier-expertise',
                            ],
                        ],
                    ],
                    'historiser-expertise' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/historiser-expertise/:expertise',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'historiser-expertise',
                            ],
                        ],
                    ],
                    'restaurer-expertise' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/restaurer-expertise/:expertise',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'restaurer-expertise',
                            ],
                        ],
                    ],
                    'supprimer-expertise' => [
                        'type'  => Segment::class,
                        'may_terminate' => true,
                        'options' => [
                            'route'    => '/supprimer-expertise/:expertise',
                            'defaults' => [
                                'controller' => FichePosteController::class,
                                'action'     => 'supprimer-expertise',
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

            ActivitesDescriptionsRetireesService::class => ActivitesDescriptionsRetireesServiceFactory::class,
            ApplicationsRetireesService::class => ApplicationsRetireesServiceFactory::class,
            CompetencesRetireesService::class => CompetencesRetireesServiceFactory::class,
            ExpertiseService::class => ExpertiseServiceFactory::class,
            FichePosteService::class => FichePosteServiceFactory::class,
            FormationsRetireesService::class => FormationsRetireesServiceFactory::class,
            SpecificitePosteService::class => SpecificitePosteServiceFactory::class,
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
            ExpertiseForm::class => ExpertiseFormFactory::class,
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
            ExpertiseHydrator::class => ExpertiseHydratorFactory::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'fichesPostesAsArray' => FichesPostesAsArrayViewHelper::class,
            'fichePosteGraph' => FichePosteGraphViewHelper::class,
        ],
    ],

];