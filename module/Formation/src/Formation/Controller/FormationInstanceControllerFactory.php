<?php

namespace Formation\Controller;

use Autoform\Service\Formulaire\FormulaireInstanceService;
use Formation\Form\FormationInstance\FormationInstanceForm;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Interop\Container\ContainerInterface;

class FormationInstanceControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var FormulaireInstanceService $formulaireInstanceService
         */
        $formationService = $container->get(FormationService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $formulaireInstanceService = $container->get(FormulaireInstanceService::class);

        /**
         * @var FormationInstanceForm $formationInstanceForm
         */
        $formationInstanceForm = $container->get('FormElementManager')->get(FormationInstanceForm::class);

        /** @var FormationInstanceController $controller */
        $controller = new FormationInstanceController();
        $controller->setFormationService($formationService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setFormulaireInstanceService($formulaireInstanceService);
        $controller->setFormationInstanceForm($formationInstanceForm);
        return $controller;
    }

}