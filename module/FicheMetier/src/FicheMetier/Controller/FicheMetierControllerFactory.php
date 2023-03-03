<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Element\Form\SelectionApplication\SelectionApplicationForm;
use Element\Form\SelectionCompetence\SelectionCompetenceForm;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationForm;
use FicheMetier\Form\Raison\RaisonForm;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use Metier\Form\SelectionnerMetier\SelectionnerMetierForm;
use Metier\Service\Domaine\DomaineService;
use Metier\Service\Metier\MetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatForm;
use UnicaenEtat\Service\Etat\EtatService;

class FicheMetierControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FicheMetierController
    {
        /**
         * @var DomaineService $domaineService
         * @var EtatService $etatService
         * @var FicheMetierService $ficheMetierService
         * @var MetierService $metierService
         * @var MissionPrincipaleService $missionPrincipaleService
         */
        $domaineService = $container->get(DomaineService::class);
        $etatService = $container->get(EtatService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $metierService = $container->get(metierService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);

        /**
         * @var FicheMetierImportationForm $importationForm
         * @var ModifierLibelleForm $modifierLibelleForm
         * @var RaisonForm $raisonForm
         * @var SelectionApplicationForm $selectionnerApplicationForm
         * @var SelectionCompetenceForm $selectionnerCompetenceForm
         * @var SelectionEtatForm $selectionnerEtatForm
         * @var SelectionnerMetierForm $selectionnerMetierForm
         */
        $importationForm = $container->get('FormElementManager')->get(FicheMetierImportationForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $selectionnerEtatForm = $container->get('FormElementManager')->get(SelectionEtatForm::class);
        $selectionnerApplicationForm = $container->get('FormElementManager')->get(SelectionApplicationForm::class);
        $selectionnerCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);
        $selectionnerMetierForm = $container->get('FormElementManager')->get(SelectionnerMetierForm::class);
        $raisonForm = $container->get('FormElementManager')->get(RaisonForm::class);

        $controller = new FicheMetierController();
        $controller->setDomaineService($domaineService);
        $controller->setEtatService($etatService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setMetierService($metierService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setFicheMetierImportationForm($importationForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setRaisonForm($raisonForm);
        $controller->setSelectionApplicationForm($selectionnerApplicationForm);
        $controller->setSelectionCompetenceForm($selectionnerCompetenceForm);
        $controller->setSelectionEtatForm($selectionnerEtatForm);
        $controller->setSelectionnerMetierForm($selectionnerMetierForm);
        return $controller;
    }
}