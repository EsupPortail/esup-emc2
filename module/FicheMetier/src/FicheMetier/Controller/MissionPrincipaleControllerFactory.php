<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeForm;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeService;
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
         * @var NiveauEnveloppeService $niveauEnveloppeService
         */
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $niveauEnveloppeService = $container->get(NiveauEnveloppeService::class);

        /**
         * @var ModifierLibelleForm $modifierLibelleForm
         * @var NiveauEnveloppeForm $niveauEnveloppeForm
         * @var SelectionnerDomainesForm $selectionDomainesForm
         */
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $niveauEnveloppeForm = $container->get('FormElementManager')->get(NiveauEnveloppeForm::class);
        $selectionDomainesForm = $container->get('FormElementManager')->get(SelectionnerDomainesForm::class);

        $controller = new MissionPrincipaleController();
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setNiveauEnveloppeService($niveauEnveloppeService);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setNiveauEnveloppeForm($niveauEnveloppeForm);
        $controller->setSelectionnerDomainesForm($selectionDomainesForm);
        return $controller;
    }
}