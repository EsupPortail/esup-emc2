<?php

namespace Agent;

use Agent\Assertion\PortfolioAssertion;
use Agent\Assertion\PortfolioAssertionFactory;
use Agent\Controller\PortfolioControler;
use Agent\Controller\PortfolioControllerFactory;
use Agent\Provider\Privilege\PortfolioPrivileges;
use Laminas\Router\Http\Literal;
use Laminas\Router\Http\Segment;
use UnicaenPrivilege\Guard\PrivilegeController;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

return [
    'bjyauthorize' => [
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            PortfolioPrivileges::PORTFOLIO_AFFICHER,
                            PortfolioPrivileges::PORTFOLIO_AFFICHER_DOCUMENT,
                            PortfolioPrivileges::PORTFOLIO_AJOUTER_DOCUMENT,
                            PortfolioPrivileges::PORTFOLIO_HISTORISER_DOCUMENT,
                            PortfolioPrivileges::PORTFOLIO_RESTAURER_DOCUMENT,
                            PortfolioPrivileges::PORTFOLIO_SUPPRIMER_DOCUMENT,
                        ],
                        'resources' => ['Agent'],
                        'assertion' => PortfolioAssertion::class
                    ],
                ],
            ],
        ],
        'guards' => [
            PrivilegeController::class => [
                [
                    'controller' => PortfolioControler::class,
                    'action' => [
                        'portfolio',
                    ],
                    'privileges' => [
                        PortfolioPrivileges::PORTFOLIO_AFFICHER
                    ],
                    'assertion' => PortfolioAssertion::class,
                ],
                [
                    'controller' => PortfolioControler::class,
                    'action' => [
                        'afficher',
                    ],
                    'privileges' => [
                        PortfolioPrivileges::PORTFOLIO_AFFICHER_DOCUMENT
                    ],
                    'assertion' => PortfolioAssertion::class,
                ],
                [
                    'controller' => PortfolioControler::class,
                    'action' => [
                        'ajouter',
                    ],
                    'privileges' => [
                        PortfolioPrivileges::PORTFOLIO_AJOUTER_DOCUMENT
                    ],
                    'assertion' => PortfolioAssertion::class,
                ],
                [
                    'controller' => PortfolioControler::class,
                    'action' => [
                        'historiser',
                    ],
                    'privileges' => [
                        PortfolioPrivileges::PORTFOLIO_HISTORISER_DOCUMENT
                    ],
                    'assertion' => PortfolioAssertion::class,
                ],
                [
                    'controller' => PortfolioControler::class,
                    'action' => [
                        'restaurer',
                    ],
                    'privileges' => [
                        PortfolioPrivileges::PORTFOLIO_RESTAURER_DOCUMENT
                    ],
                    'assertion' => PortfolioAssertion::class,
                ],
                [
                    'controller' => PortfolioControler::class,
                    'action' => [
                        'supprimer',
                    ],
                    'privileges' => [
                        PortfolioPrivileges::PORTFOLIO_SUPPRIMER_DOCUMENT
                    ],
                    'assertion' => PortfolioAssertion::class,
                ],
            ],
        ],
    ],

    'router' => [
        'routes' => [
            'agent' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/agent',
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'portfolio' => [
                        'type' => Segment::class,
                        'options' => [
                            'route' => '/portfolio/:agent',
                            'defaults' => [
                                /** @see PortfolioControler::portfolioAction() */
                                'controller' => PortfolioControler::class,
                                'action' => 'portfolio'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ajouter' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/ajouter',
                                    'defaults' => [
                                        /** @see PortfolioControler::ajouterAction() */
                                        'controller' => PortfolioControler::class,
                                        'action' => 'ajouter',
                                    ],
                                ],
                            ],
                            'afficher' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/afficher/:fichier',
                                    'defaults' => [
                                        /** @see PortfolioControler::afficherAction() */
                                        'controller' => PortfolioControler::class,
                                        'action' => 'afficher',
                                    ],
                                ],
                            ],
                            'historiser' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/historiser/:fichier',
                                    'defaults' => [
                                        /** @see PortfolioControler::historiserAction() */
                                        'controller' => PortfolioControler::class,
                                        'action' => 'historiser',
                                    ],
                                ],
                            ],
                            'restaurer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/restaurer/:fichier',
                                    'defaults' => [
                                        /** @see PortfolioControler::restaurerAction() */
                                        'controller' => PortfolioControler::class,
                                        'action' => 'restaurer',
                                    ],
                                ],
                            ],
                            'supprimer' => [
                                'type' => Segment::class,
                                'options' => [
                                    'route' => '/supprimer/:fichier',
                                    'defaults' => [
                                        /** @see PortfolioControler::supprimerAction() */
                                        'controller' => PortfolioControler::class,
                                        'action' => 'supprimer',
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
            PortfolioAssertion::class => PortfolioAssertionFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            PortfolioControler::class => PortfolioControllerFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
        ],
    ],
    'hydrators' => [
        'factories' => [
        ],
    ],

];