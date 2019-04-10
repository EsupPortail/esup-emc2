<?php

namespace Application\Controller\EntretienProfessionnel;

use Application\Form\EntretienProfessionnel\EntretienProfessionnelForm;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Autoform\Service\Formulaire\FormulaireInstanceService;
use Autoform\Service\Formulaire\FormulaireService;
use Zend\Mvc\Controller\ControllerManager;

class EntretienProfessionnelControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var EntretienProfessionnelService $entretienProfesionnelService
         * @var FormulaireService $formulaireService
         * @var FormulaireInstanceService $formulaireInstanceService
         */
        $entretienProfesionnelService = $manager->getServiceLocator()->get(EntretienProfessionnelService::class);
        $formulaireService = $manager->getServiceLocator()->get(FormulaireService::class);
        $formulaireInstanceService = $manager->getServiceLocator()->get(FormulaireInstanceService::class);

        /** @var EntretienProfessionnelForm $entretienProfessionnelForm */
        $entretienProfessionnelForm = $manager->getServiceLocator()->get('FormElementManager')->get(EntretienProfessionnelForm::class);

        /** @var EntretienProfessionnelController $controller */
        $controller = new EntretienProfessionnelController();
        $controller->setEntretienProfessionnelService($entretienProfesionnelService);
        $controller->setFormulaireInstanceService($formulaireInstanceService);
        $controller->setFormulaireService($formulaireService);
        $controller->setEntretienProfessionnelForm($entretienProfessionnelForm);
        return $controller;
    }
}