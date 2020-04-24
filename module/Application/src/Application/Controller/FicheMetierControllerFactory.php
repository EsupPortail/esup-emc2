<?php

namespace Application\Controller;

use Application\Form\Activite\ActiviteForm;
use Application\Form\FicheMetier\ActiviteExistanteForm;
use Application\Form\FicheMetier\GererCompetenceForm;
use Application\Form\FicheMetier\LibelleForm;
use Application\Form\SelectionApplication\SelectionApplicationForm;
use Application\Form\SelectionCompetence\SelectionCompetenceForm;
use Application\Form\SelectionFormation\SelectionFormationForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\Configuration\ConfigurationService;
use Application\Service\Domaine\DomaineService;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\RessourceRh\RessourceRhService;
use Interop\Container\ContainerInterface;
use Zend\View\Renderer\PhpRenderer;

class FicheMetierControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ActiviteService $activiteService
         * @var DomaineService $domaineService
         * @var FicheMetierService $ficheMetierService
         * @var RessourceRhService $ressourceRhService
         * @var ConfigurationService $configurationService
         */
        $activiteService = $container->get(ActiviteService::class);
        $domaineService = $container->get(DomaineService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $ressourceRhService = $container->get(RessourceRhService::class);
        $configurationService = $container->get(ConfigurationService::class);


        /**
         * @var LibelleForm $libelleForm
         * @var ActiviteForm $activiteForm
         * @var ActiviteExistanteForm $activiteExistanteForm
         * @var GererCompetenceForm $gererCompetenceForm
         * @var SelectionApplicationForm $selectionApplicationForm
         * @var SelectionCompetenceForm $selectionCompetenceForm
         * @var SelectionFormationForm $selectionFormationForm
         */
        $libelleForm = $container->get('FormElementManager')->get(LibelleForm::class); //2 ?
        $activiteForm = $container->get('FormElementManager')->get(ActiviteForm::class);
        $activiteExistanteForm = $container->get('FormElementManager')->get(ActiviteExistanteForm::class); //2 ?
        $selectionApplicationForm = $container->get('FormElementManager')->get(SelectionApplicationForm::class);
        $selectionCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);
        $selectionFormationForm = $container->get('FormElementManager')->get(SelectionFormationForm::class);

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var FicheMetierController $controller */
        $controller = new FicheMetierController();
        $controller->setRenderer($renderer);

        $controller->setActiviteService($activiteService);
        $controller->setDomaineService($domaineService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setRessourceRhService($ressourceRhService);
        $controller->setConfigurationService($configurationService);

        $controller->setLibelleForm($libelleForm);
        $controller->setActiviteForm($activiteForm);
        $controller->setActiviteExistanteForm($activiteExistanteForm);
        $controller->setSelectionApplicationForm($selectionApplicationForm);
        $controller->setSelectionCompetenceForm($selectionCompetenceForm);
        $controller->setSelectionFormationForm($selectionFormationForm);

        return $controller;
    }

}