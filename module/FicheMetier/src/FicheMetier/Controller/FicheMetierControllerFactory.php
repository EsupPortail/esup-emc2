<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Service\FichePoste\FichePosteService;
use Element\Form\SelectionApplication\SelectionApplicationForm;
use Element\Form\SelectionCompetence\SelectionCompetenceForm;
use FicheMetier\Form\CodeFonction\CodeFonctionForm;
use FicheMetier\Form\Raison\RaisonForm;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use FicheMetier\Service\TendanceElement\TendanceElementService;
use FicheMetier\Service\TendanceType\TendanceTypeService;
use FicheMetier\Service\ThematiqueElement\ThematiqueElementService;
use FicheMetier\Service\ThematiqueType\ThematiqueTypeService;
use Metier\Form\SelectionnerMetier\SelectionnerMetierForm;
use Metier\Service\Domaine\DomaineService;
use Metier\Service\Metier\MetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
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
         * @var DomaineService $domaineService
         * @var EtatTypeService $etatTypeService
         * @var FicheMetierService $ficheMetierService
         * @var FichePosteService $fichePosteService
         * @var MetierService $metierService
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var ParametreService $parametreService
         * @var TendanceElementService $tendanceElementService
         * @var TendanceTypeService $tendanceTypeService
         * @var ThematiqueElementService $thematiqueElementService
         * @var ThematiqueTypeService $thematiqueTypeService
         */
        $domaineService = $container->get(DomaineService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $metierService = $container->get(metierService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $parametreService = $container->get(ParametreService::class);
        $tendanceElementService = $container->get(TendanceElementService::class);
        $tendanceTypeService = $container->get(TendanceTypeService::class);
        $thematiqueElementService = $container->get(ThematiqueElementService::class);
        $thematiqueTypeService = $container->get(ThematiqueTypeService::class);

        /**
         * @var CodeFonctionForm $codeFonctionForm ;
         * @var ModifierLibelleForm $modifierLibelleForm
         * @var RaisonForm $raisonForm
         * @var SelectionApplicationForm $selectionnerApplicationForm
         * @var SelectionCompetenceForm $selectionnerCompetenceForm
         * @var SelectionEtatForm $selectionnerEtatForm
         * @var SelectionnerMetierForm $selectionnerMetierForm
         */
        $codeFonctionForm = $container->get('FormElementManager')->get(CodeFonctionForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $selectionnerEtatForm = $container->get('FormElementManager')->get(SelectionEtatForm::class);
        $selectionnerApplicationForm = $container->get('FormElementManager')->get(SelectionApplicationForm::class);
        $selectionnerCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);
        $selectionnerMetierForm = $container->get('FormElementManager')->get(SelectionnerMetierForm::class);
        $raisonForm = $container->get('FormElementManager')->get(RaisonForm::class);

        $controller = new FicheMetierController();
        $controller->setCodeFonctionForm($codeFonctionForm);
        $controller->setDomaineService($domaineService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setMetierService($metierService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setParametreService($parametreService);
        $controller->setTendanceElementService($tendanceElementService);
        $controller->setTendanceTypeService($tendanceTypeService);
        $controller->setThematiqueElementService($thematiqueElementService);
        $controller->setThematiqueTypeService($thematiqueTypeService);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setRaisonForm($raisonForm);
        $controller->setSelectionApplicationForm($selectionnerApplicationForm);
        $controller->setSelectionCompetenceForm($selectionnerCompetenceForm);
        $controller->setSelectionEtatForm($selectionnerEtatForm);
        $controller->setSelectionnerMetierForm($selectionnerMetierForm);
        return $controller;
    }
}