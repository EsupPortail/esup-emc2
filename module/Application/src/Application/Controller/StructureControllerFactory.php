<?php

namespace Application\Controller;

use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Form\AjouterGestionnaire\AjouterGestionnaireForm;
use Application\Form\Structure\StructureForm;
use Application\Service\Agent\AgentService;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\MissionSpecifique\MissionSpecifiqueAffectationService;
use Application\Service\Poste\PosteService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class StructureControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var FichePosteService $fichePosteService
         * @var MissionSpecifiqueAffectationService $missionSpecifiqueAffectationService
         * @var PosteService $posteService
         * @var RoleService $roleService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $missionSpecifiqueAffectationService = $container->get(MissionSpecifiqueAffectationService::class);
        $posteService = $container->get(PosteService::class);
        $roleService = $container->get(RoleService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var AgentMissionSpecifiqueForm $affectationForm
         * @var AjouterGestionnaireForm $ajouterGestionnaire
         * @var StructureForm $structureForm
         */
        $affectationForm = $container->get('FormElementManager')->get(AgentMissionSpecifiqueForm::class);
        $ajouterGestionnaire = $container->get('FormElementManager')->get(AjouterGestionnaireForm::class);
        $structureForm = $container->get('FormElementManager')->get(StructureForm::class);

        /** @var StructureController $controller */
        $controller = new StructureController();

        $controller->setAgentService($agentService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setMissionSpecifiqueAffectationService($missionSpecifiqueAffectationService);
        $controller->setPosteService($posteService);
        $controller->setRoleService($roleService);
        $controller->setStructureService($structureService);
        $controller->setUserService($userService);

        $controller->setAgentMissionSpecifiqueForm($affectationForm);
        $controller->setAjouterGestionnaireForm($ajouterGestionnaire);
        $controller->setStructureForm($structureForm);

        return $controller;
    }
}