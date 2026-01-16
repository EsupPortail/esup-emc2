<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Service\Agent\AgentService;
use Application\Service\FichePoste\FichePosteService;
use Carriere\Form\SelectionnerNiveauCarriere\SelectionnerNiveauCarriereForm;
use Element\Form\SelectionApplication\SelectionApplicationForm;
use Element\Form\SelectionCompetence\SelectionCompetenceForm;
use Element\Service\CompetenceType\CompetenceTypeService;
use FicheMetier\Form\CodeFonction\CodeFonctionForm;
use FicheMetier\Form\Raison\RaisonForm;
use FicheMetier\Form\SelectionnerMissionPrincipale\SelectionnerMissionPrincipaleForm;
use FicheMetier\Service\CodeFonction\CodeFonctionService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\FicheMetierMission\FicheMetierMissionService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use FicheMetier\Service\TendanceElement\TendanceElementService;
use FicheMetier\Service\TendanceType\TendanceTypeService;
use FicheMetier\Service\ThematiqueElement\ThematiqueElementService;
use FicheMetier\Service\ThematiqueType\ThematiqueTypeService;
use Metier\Service\Metier\MetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\Referentiel\ReferentielService;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatForm;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenParametre\Service\Parametre\ParametreService;

class FicheMetierControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FicheMetierController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FicheMetierController
    {
        /**
         * @var AgentService $agentService
         * @var CodeFonctionService $codeFonctionService
         * @var CompetenceTypeService $competenceTypeService
         * @var EtatTypeService $etatTypeService
         * @var FicheMetierService $ficheMetierService
         * @var FicheMetierMissionService $ficheMetierMissionService
         * @var FichePosteService $fichePosteService
         * @var MetierService $metierService
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var ParametreService $parametreService
         * @var ReferentielService $referentielService
         * @var TendanceElementService $tendanceElementService
         * @var TendanceTypeService $tendanceTypeService
         * @var ThematiqueElementService $thematiqueElementService
         * @var ThematiqueTypeService $thematiqueTypeService
         */
        $agentService = $container->get(AgentService::class);
        $codeFonctionService = $container->get(CodeFonctionService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $ficheMetierMissionService = $container->get(FicheMetierMissionService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $metierService = $container->get(metierService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $parametreService = $container->get(ParametreService::class);
        $referentielService = $container->get(ReferentielService::class);
        $tendanceElementService = $container->get(TendanceElementService::class);
        $tendanceTypeService = $container->get(TendanceTypeService::class);
        $thematiqueElementService = $container->get(ThematiqueElementService::class);
        $thematiqueTypeService = $container->get(ThematiqueTypeService::class);

        /**
         * @var CodeFonctionForm $codeFonctionForm
         * @var ModifierLibelleForm $modifierLibelleForm
         * @var RaisonForm $raisonForm
         * @var SelectionApplicationForm $selectionnerApplicationForm
         * @var SelectionCompetenceForm $selectionnerCompetenceForm
         * @var SelectionEtatForm $selectionnerEtatForm
         * @var SelectionnerMissionPrincipaleForm $selectionnerMissionPrincipaleForm
         * @var SelectionnerNiveauCarriereForm $selectionnerNiveauCarriereForm
         */
        $codeFonctionForm = $container->get('FormElementManager')->get(CodeFonctionForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $selectionnerEtatForm = $container->get('FormElementManager')->get(SelectionEtatForm::class);
        $selectionnerApplicationForm = $container->get('FormElementManager')->get(SelectionApplicationForm::class);
        $selectionnerCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);
        $selectionnerMissionPrincipaleForm = $container->get('FormElementManager')->get(SelectionnerMissionPrincipaleForm::class);
        $selectionnerNiveauCarriereForm = $container->get('FormElementManager')->get(SelectionnerNiveauCarriereForm::class);
        $raisonForm = $container->get('FormElementManager')->get(RaisonForm::class);

        $controller = new FicheMetierController();
        $controller->setAgentService($agentService);
        $controller->setCodeFonctionService($codeFonctionService);
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFicheMetierMissionService($ficheMetierMissionService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setMetierService($metierService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setParametreService($parametreService);
        $controller->setReferentielService($referentielService);
        $controller->setTendanceElementService($tendanceElementService);
        $controller->setTendanceTypeService($tendanceTypeService);
        $controller->setThematiqueElementService($thematiqueElementService);
        $controller->setThematiqueTypeService($thematiqueTypeService);
        $controller->setCodeFonctionForm($codeFonctionForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setRaisonForm($raisonForm);
        $controller->setSelectionApplicationForm($selectionnerApplicationForm);
        $controller->setSelectionCompetenceForm($selectionnerCompetenceForm);
        $controller->setSelectionEtatForm($selectionnerEtatForm);
        $controller->setSelectionnerMissionPrincipaleForm($selectionnerMissionPrincipaleForm);
        $controller->setSelectionnerNiveauCarriereForm($selectionnerNiveauCarriereForm);
        return $controller;
    }
}