<?php

namespace Application\Controller;

use Application\Form\Activite\ActiviteForm;
use Application\Form\FicheMetier\ActiviteExistanteForm;
use Application\Form\FicheMetier\LibelleForm;
use Application\Form\SelectionApplication\SelectionApplicationForm;
use Application\Form\SelectionCompetence\SelectionCompetenceForm;
use Application\Service\HasApplicationCollection\HasApplicationCollectionService;
use Application\Service\HasCompetenceCollection\HasCompetenceCollectionService;
use Formation\Form\SelectionFormation\SelectionFormationForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\Configuration\ConfigurationService;
use Application\Service\Domaine\DomaineService;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\Metier\MetierService;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use Interop\Container\ContainerInterface;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatForm;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use Zend\View\Renderer\PhpRenderer;

class FicheMetierControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ActiviteService $activiteService
         * @var DomaineService $domaineService
         * @var EtatService $etatService
         * @var EtatTypeService $etatTypeService
         * @var FicheMetierService $ficheMetierService
         * @var HasApplicationCollectionService $hasApplicationCollectionService
         * @var HasCompetenceCollectionService $hasCompetenceCollectionService
         * @var ConfigurationService $configurationService
         * @var MetierService $metierService
         * @var ParcoursDeFormationService $parcoursService
         */
        $activiteService = $container->get(ActiviteService::class);
        $domaineService = $container->get(DomaineService::class);
        $etatService = $container->get(EtatService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $hasApplicationCollectionService = $container->get(HasApplicationCollectionService::class);
        $hasCompetenceCollectionService = $container->get(HasCompetenceCollectionService::class);
        $configurationService = $container->get(ConfigurationService::class);
        $metierService = $container->get(MetierService::class);
        $parcoursService = $container->get(ParcoursDeFormationService::class);

        /**
         * @var LibelleForm $libelleForm
         * @var ActiviteForm $activiteForm
         * @var ActiviteExistanteForm $activiteExistanteForm
         * @var SelectionApplicationForm $selectionApplicationForm
         * @var SelectionCompetenceForm $selectionCompetenceForm
         * @var SelectionFormationForm $selectionFormationForm
         * @var SelectionEtatForm $selectionEtatForm
         */
        $libelleForm = $container->get('FormElementManager')->get(LibelleForm::class); //2 ?
        $activiteForm = $container->get('FormElementManager')->get(ActiviteForm::class);
        $activiteExistanteForm = $container->get('FormElementManager')->get(ActiviteExistanteForm::class); //2 ?
        $selectionApplicationForm = $container->get('FormElementManager')->get(SelectionApplicationForm::class);
        $selectionCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);
        $selectionFormationForm = $container->get('FormElementManager')->get(SelectionFormationForm::class);
        $selectionEtatForm = $container->get('FormElementManager')->get(SelectionEtatForm::class);

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var FicheMetierController $controller */
        $controller = new FicheMetierController();
        $controller->setRenderer($renderer);

        $controller->setActiviteService($activiteService);
        $controller->setDomaineService($domaineService);
        $controller->setEtatService($etatService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setHasApplicationCollectionService($hasApplicationCollectionService);
        $controller->setHasCompetenceCollectionService($hasCompetenceCollectionService);
        $controller->setConfigurationService($configurationService);
        $controller->setMetierService($metierService);
        $controller->setParcoursDeFormationService($parcoursService);

        $controller->setLibelleForm($libelleForm);
        $controller->setActiviteForm($activiteForm);
        $controller->setActiviteExistanteForm($activiteExistanteForm);
        $controller->setSelectionApplicationForm($selectionApplicationForm);
        $controller->setSelectionCompetenceForm($selectionCompetenceForm);
        $controller->setSelectionFormationForm($selectionFormationForm);
        $controller->setSelectionEtatForm($selectionEtatForm);

        return $controller;
    }

}