<?php

namespace FicheMetier\Controller;

use Element\Form\SelectionCompetence\SelectionCompetenceForm;
use Element\Service\Competence\CompetenceService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\FicheMetierConfigurationCompetenceParDefaut\FicheMetierConfigurationCompetenceParDefautService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class FicheMetierConfigurationCompetenceParDefautControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FicheMetierConfigurationCompetenceParDefautController
    {
        /**
         * @var CompetenceService $competenceService
         * @var FicheMetierService $ficheMetierService
         * @var FicheMetierConfigurationCompetenceParDefautService $ficheMetierConfigurationCompetenceParDefautService
         * @var ParametreService $parametreService
         */
        $competenceService = $container->get(CompetenceService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $ficheMetierConfigurationCompetenceParDefautService = $container->get(FicheMetierConfigurationCompetenceParDefautService::class);
        $parametreService = $container->get(ParametreService::class);

        /**
         * @var SelectionCompetenceForm $selectionCompetenceForm
         */
        $selectionCompetenceForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);

        $controller = new FicheMetierConfigurationCompetenceParDefautController();
        $controller->setCompetenceService($competenceService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setFicheMetierConfigurationCompetenceParDefautService($ficheMetierConfigurationCompetenceParDefautService);
        $controller->setParametreService($parametreService);
        $controller->setSelectionCompetenceForm($selectionCompetenceForm);
        return $controller;

    }
}
