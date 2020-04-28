<?php

namespace Autoform\Controller;

use Autoform\Service\Formulaire\FormulaireInstanceService;
use Autoform\Service\Formulaire\FormulaireReponseService;
use Autoform\Service\Validation\ValidationReponseService;
use Autoform\Service\Validation\ValidationService;
use Interop\Container\ContainerInterface;
use Zend\View\Renderer\PhpRenderer;

class ValidationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ValidationController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormulaireInstanceService $formulaireInstanceService
         * @var FormulaireReponseService $formulaireReponseService
         * @var ValidationService $validationService
         * @var ValidationReponseService $validationReponseService
         */
        $formulaireInstanceService   = $container->get(FormulaireInstanceService::class);
        $formulaireReponseService   = $container->get(FormulaireReponseService::class);
        $validationService          = $container->get(ValidationService::class);
        $validationReponseService   = $container->get(ValidationReponseService::class);

        /** @var ValidationController $controller */
        $controller = new ValidationController();
        $controller->setFormulaireInstanceService($formulaireInstanceService);
        $controller->setFormulaireReponseService($formulaireReponseService);
        $controller->setValidationService($validationService);
        $controller->setValidationReponseService($validationReponseService);

        /* @var $renderer PhpRenderer */
        $controller->renderer = $container->get('ViewRenderer');

        return $controller;
    }
}