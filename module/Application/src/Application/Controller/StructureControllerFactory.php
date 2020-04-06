<?php

namespace Application\Controller;

use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Form\Structure\StructureForm;
use Application\Service\Agent\AgentService;
use Application\Service\MissionSpecifique\MissionSpecifiqueService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;;
use Zend\Mvc\Controller\ControllerManager;

class StructureControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var MissionSpecifiqueService $missionSpecifiqueService
         * @var RoleService $roleService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $roleService = $container->get(RoleService::class);
        $agentService = $container->get(AgentService::class);
        $missionSpecifiqueService = $container->get(MissionSpecifiqueService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var AgentMissionSpecifiqueForm $affectationForm
         * @var StructureForm $structureForm
         */
        $affectationForm = $container->get('FormElementManager')->get(AgentMissionSpecifiqueForm::class);
        $structureForm = $container->get('FormElementManager')->get(StructureForm::class);

        /** @var StructureController $controller */
        $controller = new StructureController();
        $controller->setRoleService($roleService);
        $controller->setAgentService($agentService);
        $controller->setMissionSpecifiqueService($missionSpecifiqueService);
        $controller->setStructureService($structureService);
        $controller->setUserService($userService);
        $controller->setAgentMissionSpecifiqueForm($affectationForm);
        $controller->setStructureForm($structureForm);
        return $controller;
    }
}