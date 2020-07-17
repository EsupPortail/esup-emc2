<?php

namespace Application\Controller;

use Application\Form\Activite\ActiviteForm;
use Application\Form\FicheMetier\ActiviteExistanteForm;
use Application\Form\FicheMetier\LibelleForm;
use Application\Form\FicheMetierEtat\FicheMetierEtatForm;
use Application\Form\SelectionApplication\SelectionApplicationForm;
use Application\Form\SelectionCompetence\SelectionCompetenceForm;
use Application\Form\SelectionFicheMetierEtat\SelectionFicheMetierEtatForm;
use Application\Form\SelectionFormation\SelectionFormationForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\Configuration\ConfigurationService;
use Application\Service\Domaine\DomaineService;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\FicheMetierEtat\FicheMetierEtatService;
use Application\Service\Metier\MetierService;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use Interop\Container\ContainerInterface;
use Zend\View\Renderer\PhpRenderer;

class FicheMetierControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ActiviteService $activiteService
         * @var DomaineService $domaineService
         * @var FicheMetierService $ficheMetierService
         * @var FicheMetierEtatService $ficheMetierEtatService
         * @var ConfigurationService $configurationService
         * @var MetierService $metierService
         * @var ParcoursDeFormationService $parcoursService
         */
        $activiteService = $container->get(ActiviteService::class);
        $domaineService = $container->get(DomaineService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $ficheMetierEtatService = $container->get(FicheMetierEtatService::class);
        $configurationService = $container->get(ConfigurationService::class);
        $metierService = $container->get(MetierService::class);
        $parcoursService = $container->get(ParcoursDeFormationService::class);

        /**
         * @var LibelleForm $libelleForm
         * @var ActiviteForm $activiteForm
         * @var ActiviteExistanteForm $activiteExistanteForm
         * @var FicheMetierEtatForm $ficheMetierEtatForm
         * @var SelectionApplicationForm $selectionApplicationForm
         * @var SelectionCompetenceForm $selectionCompetenceForm
         * @var SelectionFicheMetierEtatForm $selectionFicheMetierForm
         * @var SelectionFormationForm $selectionFormationForm
         */
        $libelleForm = $container->get('FormElementManager')->get(LibelleForm::class); //2 ?
        $activiteForm = $container->get('FormElementManager')->get(ActiviteForm::class);
        $activiteExistanteForm = $container->get('FormElementManager')->get(ActiviteExistanteForm::class); //2 ?
        $ficheMetierEtatForm = $container->get('FormElementManager')->get(FicheMetierEtatForm::class);
        $selectionApplicationForm = $container->get('FormElementManager')->get(SelectionApplicationForm::class);
        $selectionCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);
        $selectionFicheMetierForm = $container->get('FormElementManager')->get(SelectionFicheMetierEtatForm::class);
        $selectionFormationForm = $container->get('FormElementManager')->get(SelectionFormationForm::class);

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var FicheMetierController $controller */
        $controller = new FicheMetierController();
        $controller->setRenderer($renderer);

        $controller->setActiviteService($activiteService);
        $controller->setDomaineService($domaineService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFicheMetierEtatService($ficheMetierEtatService);
        $controller->setConfigurationService($configurationService);
        $controller->setMetierService($metierService);
        $controller->setParcoursDeFormationService($parcoursService);

        $controller->setLibelleForm($libelleForm);
        $controller->setActiviteForm($activiteForm);
        $controller->setActiviteExistanteForm($activiteExistanteForm);
        $controller->setFicheMetierEtatForm($ficheMetierEtatForm);
        $controller->setSelectionApplicationForm($selectionApplicationForm);
        $controller->setSelectionCompetenceForm($selectionCompetenceForm);
        $controller->setSelectionFicheMetierEtatForm($selectionFicheMetierForm);
        $controller->setSelectionFormationForm($selectionFormationForm);

        return $controller;
    }

}