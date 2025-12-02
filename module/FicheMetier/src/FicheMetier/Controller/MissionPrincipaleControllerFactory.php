<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeForm;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeService;
use Element\Form\SelectionApplication\SelectionApplicationForm;
use Element\Form\SelectionCompetence\SelectionCompetenceForm;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\CompetenceElement\CompetenceElementService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\MissionActivite\MissionActiviteService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use Fichier\Service\Fichier\FichierService;
use Metier\Form\SelectionnerFamilleProfessionnelle\SelectionnerFamilleProfessionnelleForm;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Referentiel\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionPrincipaleControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return MissionPrincipaleController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MissionPrincipaleController
    {
        /**
         * @var ApplicationElementService $applicationElementService
         * @var CompetenceElementService $competenceElementService
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         * @var FicheMetierService $ficheMetierService
         * @var FichierService $fichierService
         * @var MissionActiviteService $missionActiviteService
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var NiveauEnveloppeService $niveauEnveloppeService
         * @var ReferentielService $referentielService
         */
        $applicationElementService = $container->get(ApplicationElementService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $fichierService = $container->get(FichierService::class);
        $missionActiviteService = $container->get(MissionActiviteService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $niveauEnveloppeService = $container->get(NiveauEnveloppeService::class);
        $referentielService = $container->get(ReferentielService::class);

        /**
         * @var ModifierLibelleForm $modifierLibelleForm
         * @var NiveauEnveloppeForm $niveauEnveloppeForm
         * @var SelectionApplicationForm $selectionApplicationForm
         * @var SelectionCompetenceForm $selectionCompetencesForm
         * @var SelectionnerFamilleProfessionnelleForm $selectionnerFamillesProfessionnellesForm
         */
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $niveauEnveloppeForm = $container->get('FormElementManager')->get(NiveauEnveloppeForm::class);
        $selectionApplicationForm = $container->get('FormElementManager')->get(SelectionApplicationForm::class);
        $selectionCompetencesForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);
        $selectionnerFamillesProfessionnellesForm = $container->get('FormElementManager')->get(SelectionnerFamilleProfessionnelleForm::class);

        $controller = new MissionPrincipaleController();
        $controller->setApplicationElementService($applicationElementService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setFamilleProfessionnelleService($familleProfessionnelleService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFichierService($fichierService);
        $controller->setMissionActiviteService($missionActiviteService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setNiveauEnveloppeService($niveauEnveloppeService);
        $controller->setReferentielService($referentielService);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setNiveauEnveloppeForm($niveauEnveloppeForm);
        $controller->setSelectionApplicationForm($selectionApplicationForm);
        $controller->setSelectionCompetenceForm($selectionCompetencesForm);
        $controller->setSelectionnerFamilleProfessionnelleForm($selectionnerFamillesProfessionnellesForm);
        return $controller;
    }
}