<?php

namespace Formation\Controller;

use Formation\Form\FormationInstanceFrais\FormationInstanceFraisForm;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceFrais\FormationInstanceFraisService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Formation\Service\Seance\SeanceService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationInstanceFraisControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceFraisController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationInstanceFraisController
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var FormationInstanceFraisService $formationInstanceFraisService
         * @var SeanceService $seanceService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $formationInstanceFraisService = $container->get(FormationInstanceFraisService::class);
        $seanceService = $container->get(SeanceService::class);

        /**
         * @var FormationInstanceFraisForm $foramtionInstanceFraisForm
         */
        $formationInstanceFraisForm = $container->get('FormElementManager')->get(FormationInstanceFraisForm::class);

        $controller = new FormationInstanceFraisController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setFormationInstanceFraisService($formationInstanceFraisService);
        $controller->setFormationInstanceFraisForm($formationInstanceFraisForm);
        $controller->setSeanceService($seanceService);

        return $controller;
    }
}