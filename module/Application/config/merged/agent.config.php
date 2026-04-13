<?php

namespace Application;

use Agent\Assertion\AgentAssertion;
use Agent\Provider\Privilege\AgentPrivileges;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormFactory;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueHydrator;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueHydratorFactory;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueServiceFactory;
use EntretienProfessionnel\Provider\Privilege\EntretienproPrivileges;
use UnicaenPrivilege\Provider\Rule\PrivilegeRuleProvider;

return [
    'bjyauthorize' => [
        'resource_providers' => [
            'BjyAuthorize\Provider\Resource\Config' => [
                'Agent' => [],
            ],
        ],
        'rule_providers' => [
            PrivilegeRuleProvider::class => [
                'allow' => [
                    [
                        'privileges' => [
                            AgentPrivileges::AGENT_AFFICHER,
                            AgentPrivileges::AGENT_ELEMENT_VOIR,
                            AgentPrivileges::AGENT_ELEMENT_AJOUTER,
                            AgentPrivileges::AGENT_ELEMENT_MODIFIER,
                            AgentPrivileges::AGENT_ELEMENT_HISTORISER,
                            AgentPrivileges::AGENT_ELEMENT_DETRUIRE,
                            AgentPrivileges::AGENT_ELEMENT_VALIDER,
                            AgentPrivileges::AGENT_ACQUIS_AFFICHER,
                            AgentPrivileges::AGENT_ACQUIS_MODIFIER,
                        ],
                        'resources' => ['Agent'],
                        'assertion' => AgentAssertion::class
                    ],
                ],
            ],
        ],
    ],


    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'suivis' => [
                        'order' => 1,
                        'label' => 'Suivis',
                        'title' => "suivis",
                        'route' => 'agent',
//                        'resource' => PrivilegeController::getResourceId(AgentController::class, 'index'),
                        'pages' => [
                        ],
                    ],
                    'entretien' => [
                        'order' => 0101,
                        'label' => 'Entretiens professionnels',
                        'title' => "Gestion des données d'un agent",
                        'route' => 'entretien-professionnel/index-agent',
                        'resource' => AgentPrivileges::getResourceId(EntretienproPrivileges::ENTRETIENPRO_MESENTRETIENS),
                    ],
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            AgentMissionSpecifiqueService::class => AgentMissionSpecifiqueServiceFactory::class,

        ],
    ],
    'controllers' => [
        'factories' => [
        ],
    ],
    'form_elements' => [
        'factories' => [
            AgentMissionSpecifiqueForm::class => AgentMissionSpecifiqueFormFactory::class,
        ],
    ],
    'hydrators' => [
        'factories' => [
            AgentMissionSpecifiqueHydrator::class => AgentMissionSpecifiqueHydratorFactory::class,
        ],
    ],
];
