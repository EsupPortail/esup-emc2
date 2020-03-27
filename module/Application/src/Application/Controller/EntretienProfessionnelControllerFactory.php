<?php

namespace Application\Controller;

use Application\Form\EntretienProfessionnel\EntretienProfessionnelForm;
use Application\Service\Agent\AgentService;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Autoform\Service\Formulaire\FormulaireInstanceService;
use Autoform\Service\Formulaire\FormulaireService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;
use Zend\Mvc\Controller\ControllerManager;

class EntretienProfessionnelControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var UserService $userService
         * @var EntretienProfessionnelService $entretienProfesionnelService
         * @var FormulaireService $formulaireService
         * @var FormulaireInstanceService $formulaireInstanceService
         */
        $agentService = $container->get(AgentService::class);
        $userService = $container->get(UserService::class);
        $entretienProfesionnelService = $container->get(EntretienProfessionnelService::class);
        $formulaireService = $container->get(FormulaireService::class);
        $formulaireInstanceService = $container->get(FormulaireInstanceService::class);

        /** @var EntretienProfessionnelForm $entretienProfessionnelForm */
        $entretienProfessionnelForm = $container->get('FormElementManager')->get(EntretienProfessionnelForm::class);

        /** @var EntretienProfessionnelController $controller */
        $controller = new EntretienProfessionnelController();
        $controller->setAgentService($agentService);
        $controller->setUserService($userService);
        $controller->setEntretienProfessionnelService($entretienProfesionnelService);
        $controller->setFormulaireInstanceService($formulaireInstanceService);
        $controller->setFormulaireService($formulaireService);
        $controller->setEntretienProfessionnelForm($entretienProfessionnelForm);
        return $controller;
    }
}