<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeForm;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeService;
use Element\Form\SelectionApplication\SelectionApplicationForm;
use Element\Form\SelectionCompetence\SelectionCompetenceForm;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\MissionActivite\MissionActiviteService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use FicheReferentiel\Form\Importation\ImportationForm;
use Fichier\Service\Fichier\FichierService;
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
         * @var FicheMetierService $ficheMetierService
         * @var FichierService $fichierService
         * @var MissionActiviteService $missionActiviteService
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var NiveauEnveloppeService $niveauEnveloppeService
         */
        $ficheMetierService = $container->get(FicheMetierService::class);
        $fichierService = $container->get(FichierService::class);
        $missionActiviteService = $container->get(MissionActiviteService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $niveauEnveloppeService = $container->get(NiveauEnveloppeService::class);

        /**
         * @var ImportationForm $importationForm
         * @var ModifierLibelleForm $modifierLibelleForm
         * @var NiveauEnveloppeForm $niveauEnveloppeForm
         * @var SelectionApplicationForm $selectionApplicationForm
         * @var SelectionCompetenceForm $selectionCompetencesForm
         * @var SelectionnerDomainesForm $selectionDomainesForm
         */
        $importationForm = $container->get('FormElementManager')->get(ImportationForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $niveauEnveloppeForm = $container->get('FormElementManager')->get(NiveauEnveloppeForm::class);
        $selectionApplicationForm = $container->get('FormElementManager')->get(SelectionApplicationForm::class);
        $selectionCompetencesForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);
        $selectionDomainesForm = $container->get('FormElementManager')->get(SelectionnerDomainesForm::class);

        $controller = new MissionPrincipaleController();
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFichierService($fichierService);
        $controller->setMissionActiviteService($missionActiviteService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setNiveauEnveloppeService($niveauEnveloppeService);
        $controller->setImportationForm($importationForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setNiveauEnveloppeForm($niveauEnveloppeForm);
        $controller->setSelectionApplicationForm($selectionApplicationForm);
        $controller->setSelectionCompetenceForm($selectionCompetencesForm);
        $controller->setSelectionnerDomainesForm($selectionDomainesForm);
        return $controller;
    }
}