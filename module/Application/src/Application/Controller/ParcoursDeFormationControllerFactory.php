<?php

namespace Application\Controller;

use Application\Form\AjouterFormation\AjouterFormationForm;
use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Form\ModifierRattachement\ModifierRattachementForm;
use Application\Form\ParcoursDeFormation\ParcoursDeFormationForm;
use Application\Service\Formation\FormationService;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use Interop\Container\ContainerInterface;

class ParcoursDeFormationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ParcoursDeFormationController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationService $formationService
         * @var ParcoursDeFormationService $parcoursService
         */
        $formationService = $container->get(FormationService::class);
        $parcoursService = $container->get(ParcoursDeFormationService::class);

        /**
         * @var AjouterFormationForm $ajouterFormationForm
         * @var ParcoursDeFormationForm $parcoursForm
         * @var ModifierLibelleForm $modifierLibelleForm
         * @var ModifierRattachementForm $modifierRattachementForm
         */
        $ajouterFormationForm = $container->get('FormElementManager')->get(AjouterFormationForm::class);
        $parcoursForm = $container->get('FormElementManager')->get(ParcoursDeFormationForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $modifierRattachementForm = $container->get('FormElementManager')->get(ModifierRattachementForm::class);

        /** @var ParcoursDeFormationController $controller */
        $controller = new ParcoursDeFormationController();
        $controller->setFormationService($formationService);
        $controller->setParcoursDeFormationService($parcoursService);
        $controller->setAjouterFormationForm($ajouterFormationForm);
        $controller->setParcoursDeFormationForm($parcoursForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setModifierRattachementForm($modifierRattachementForm);
        return $controller;
    }
}