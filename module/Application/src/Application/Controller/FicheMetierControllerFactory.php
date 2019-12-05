<?php

namespace Application\Controller;

use Application\Form\Activite\ActiviteForm;
use Application\Form\FicheMetier\ActiviteExistanteForm;
use Application\Form\FicheMetier\ApplicationsForm;
use Application\Form\FicheMetier\FormationBaseForm;
use Application\Form\FicheMetier\FormationComportementaleForm;
use Application\Form\FicheMetier\FormationOperationnelleForm;
use Application\Form\FicheMetier\FormationsForm;
use Application\Form\FicheMetier\GererCompetenceForm;
use Application\Form\FicheMetier\LibelleForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\Configuration\ConfigurationService;
use Application\Service\Domaine\DomaineService;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\RessourceRh\RessourceRhService;
use Interop\Container\ContainerInterface;

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
         * @var ApplicationsForm $applicationsForm
         * @var FormationsForm $formationsForm
         * @var GererCompetenceForm $gererCompetenceForm
         *
         * @var FormationBaseForm $formationBaseForm
         * @var FormationComportementaleForm $formationComportementalForm
         * @var FormationOperationnelleForm $formationOperationnelleForm
         */
        $libelleForm = $container->get('FormElementManager')->get(LibelleForm::class);
        $activiteForm = $container->get('FormElementManager')->get(ActiviteForm::class);
        $activiteExistanteForm = $container->get('FormElementManager')->get(ActiviteExistanteForm::class);
        $applicationsForm = $container->get('FormElementManager')->get(ApplicationsForm::class);
        $formationsForm = $container->get('FormElementManager')->get(FormationsForm::class);
        $gererCompetenceForm = $container->get('FormElementManager')->get(GererCompetenceForm::class);

        $formationBaseForm = $container->get('FormElementManager')->get(FormationBaseForm::class);
        $formationComportementalForm = $container->get('FormElementManager')->get(FormationComportementaleForm::class);
        $formationOperationnelleForm = $container->get('FormElementManager')->get(FormationOperationnelleForm::class);



        /** @var FicheMetierController $controller */
        $controller = new FicheMetierController();

        $controller->setActiviteService($activiteService);
        $controller->setDomaineService($domaineService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setRessourceRhService($ressourceRhService);
        $controller->setConfigurationService($configurationService);

        $controller->setLibelleForm($libelleForm);
        $controller->setActiviteForm($activiteForm);
        $controller->setActiviteExistanteForm($activiteExistanteForm);
        $controller->setApplicationsForm($applicationsForm);
        $controller->setFormationsForm($formationsForm);
        $controller->setGererCompetenceForm($gererCompetenceForm);

        $controller->setFormationBaseForm($formationBaseForm);
        $controller->setFormationComportementaleForm($formationComportementalForm);
        $controller->setFormationOperationnelleForm($formationOperationnelleForm);



        return $controller;
    }

}