<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use Metier\Form\SelectionnerDomaines\SelectionnerDomainesForm;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionPrincipaleControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return MissionPrincipaleController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : MissionPrincipaleController
    {
        /**
         * @var MissionPrincipaleService $missionPrincipaleService
         */
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);

        /**
         * @var ModifierLibelleForm $modifierLibelleForm
         * @var SelectionnerDomainesForm $selectionDomainesForm
         */
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $selectionDomainesForm = $container->get('FormElementManager')->get(SelectionnerDomainesForm::class);

        $controller = new MissionPrincipaleController();
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setSelectionnerDomainesForm($selectionDomainesForm);
        return $controller;
    }
}