<?php

namespace Formation\Controller;

use Formation\Form\FormationInstanceFrais\FormationInstanceFraisForm;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceFrais\FormationInstanceFraisService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeService;
use Interop\Container\ContainerInterface;

class FormationInstanceFraisControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceFraisController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var FormationInstanceJourneeService $formationInstanceJourneeService
         * @var FormationInstanceFraisService $formationInstanceFraisService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $formationInstanceJourneeService = $container->get(FormationInstanceJourneeService::class);
        $formationInstanceFraisService = $container->get(FormationInstanceFraisService::class);

        /**
         * @var FormationInstanceFraisForm $foramtionInstanceFraisForm
         */
        $formationInstanceFraisForm = $container->get('FormElementManager')->get(FormationInstanceFraisForm::class);

        $controller = new FormationInstanceFraisController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setFormationInstanceJourneeService($formationInstanceJourneeService);
        $controller->setFormationInstanceFraisService($formationInstanceFraisService);
        $controller->setFormationInstanceFraisForm($formationInstanceFraisForm);
        return $controller;
    }
}