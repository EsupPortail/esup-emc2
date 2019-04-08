<?php

namespace Autoform\Controller;

use Autoform\Service\Formulaire\FormulaireInstanceService;
use Autoform\Service\Formulaire\FormulaireReponseService;
use Autoform\Service\Formulaire\FormulaireService;
use Autoform\Service\Validation\ValidationReponseService;
use Autoform\Service\Validation\ValidationService;
use Zend\Mvc\Controller\ControllerManager;

class ValidationControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var FormulaireService $formulaireService
         * @var FormulaireInstanceService $formulaireInstanceService
         * @var FormulaireReponseService $formulaireReponseService
         * @var ValidationService $validationService
         * @var ValidationReponseService $validationReponseService
         */
        $formulaireService          = $manager->getServiceLocator()->get(FormulaireService::class);
        $formulaireInstanceService  = $manager->getServiceLocator()->get(FormulaireInstanceService::class);
        $formulaireReponseService   = $manager->getServiceLocator()->get(FormulaireReponseService::class);
        $validationService          = $manager->getServiceLocator()->get(ValidationService::class);
        $validationReponseService   = $manager->getServiceLocator()->get(ValidationReponseService::class);

        /** @var ValidationController $controller */
        $controller = new ValidationController();
        $controller->setFormulaireService($formulaireService);
        $controller->setFormulaireInstanceService($formulaireInstanceService);
        $controller->setFormulaireReponseService($formulaireReponseService);
        $controller->setValidationService($validationService);
        $controller->setValidationReponseService($validationReponseService);
        return $controller;
    }
}