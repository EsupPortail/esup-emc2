<?php

namespace Application\Controller;

use Application\Form\Activite\ActiviteForm;
use Application\Form\SelectionFicheMetier\SelectionFicheMetierForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\ActiviteDescription\ActiviteDescriptionService;
use Application\Service\Agent\AgentService;
use Application\Service\Configuration\ConfigurationService;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use Element\Form\SelectionApplication\SelectionApplicationForm;
use Element\Form\SelectionCompetence\SelectionCompetenceForm;
use Element\Service\HasApplicationCollection\HasApplicationCollectionService;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionService;
use Formation\Form\SelectionFormation\SelectionFormationForm;
use Interop\Container\ContainerInterface;
use Metier\Form\SelectionnerMetier\SelectionnerMetierForm;
use Metier\Service\Domaine\DomaineService;
use Metier\Service\Metier\MetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Form\SelectionEtat\SelectionEtatForm;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenRenderer\Service\Rendu\RenduService;

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
         * @var ActiviteService $activiteService
         * @var ActiviteDescriptionService $activiteDescriptionService
         * @var AgentService $agentService ;
         * @var RenduService $renduService ;
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
        $activiteDescriptionService = $container->get(ActiviteDescriptionService::class);
        $agentService = $container->get(AgentService::class);
        $renduService = $container->get(RenduService::class);
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
         * @var ActiviteForm $activiteForm
         * @var SelectionApplicationForm $selectionApplicationForm
         * @var SelectionCompetenceForm $selectionCompetenceForm
         * @var SelectionFormationForm $selectionFormationForm
         * @var SelectionEtatForm $selectionEtatForm
         * @var SelectionFicheMetierForm $selectionFicheMetierForm
         * @var SelectionnerMetierForm $selectionnerMetierForm
         */
        $activiteForm = $container->get('FormElementManager')->get(ActiviteForm::class);
        $selectionApplicationForm = $container->get('FormElementManager')->get(SelectionApplicationForm::class);
        $selectionCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);
        $selectionFormationForm = $container->get('FormElementManager')->get(SelectionFormationForm::class);
        $selectionEtatForm = $container->get('FormElementManager')->get(SelectionEtatForm::class);
        $selectionFicheMetierForm = $container->get('FormElementManager')->get(SelectionFicheMetierForm::class);
        $selectionnerMetierForm = $container->get('FormElementManager')->get(SelectionnerMetierForm::class);

        /** @var FicheMetierController $controller */
        $controller = new FicheMetierController();

        $controller->setActiviteService($activiteService);
        $controller->setActiviteDescriptionService($activiteDescriptionService);
        $controller->setAgentService($agentService);
        $controller->setRenduService($renduService);
        $controller->setDomaineService($domaineService);
        $controller->setEtatService($etatService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setHasApplicationCollectionService($hasApplicationCollectionService);
        $controller->setHasCompetenceCollectionService($hasCompetenceCollectionService);
        $controller->setConfigurationService($configurationService);
        $controller->setMetierService($metierService);
        $controller->setParcoursDeFormationService($parcoursService);

        $controller->setActiviteForm($activiteForm);
        $controller->setSelectionApplicationForm($selectionApplicationForm);
        $controller->setSelectionCompetenceForm($selectionCompetenceForm);
        $controller->setSelectionFormationForm($selectionFormationForm);
        $controller->setSelectionEtatForm($selectionEtatForm);
        $controller->setSelectionFicheMetierForm($selectionFicheMetierForm);
        $controller->setSelectionnerMetierForm($selectionnerMetierForm);

        return $controller;
    }

}