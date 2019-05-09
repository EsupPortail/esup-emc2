<?php

namespace Application\Controller\EntretienProfessionnel;

use Application\Form\EntretienProfessionnel\EntretienProfessionnelForm;
use Application\Service\Agent\AgentService;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Autoform\Service\Formulaire\FormulaireInstanceService;
use Autoform\Service\Formulaire\FormulaireService;
use Utilisateur\Service\User\UserService;
use Zend\Mvc\Controller\ControllerManager;

class EntretienProfessionnelControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var AgentService $agentService
         * @var UserService $userService
         * @var EntretienProfessionnelService $entretienProfesionnelService
         * @var FormulaireService $formulaireService
         * @var FormulaireInstanceService $formulaireInstanceService
         */
        $agentService = $manager->getServiceLocator()->get(AgentService::class);
        $userService = $manager->getServiceLocator()->get(UserService::class);
        $entretienProfesionnelService = $manager->getServiceLocator()->get(EntretienProfessionnelService::class);
        $formulaireService = $manager->getServiceLocator()->get(FormulaireService::class);
        $formulaireInstanceService = $manager->getServiceLocator()->get(FormulaireInstanceService::class);

        /** @var EntretienProfessionnelForm $entretienProfessionnelForm */
        $entretienProfessionnelForm = $manager->getServiceLocator()->get('FormElementManager')->get(EntretienProfessionnelForm::class);

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