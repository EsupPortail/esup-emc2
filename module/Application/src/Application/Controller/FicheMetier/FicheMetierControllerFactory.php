<?php

namespace Application\Controller\FicheMetier;

use Application\Form\Activite\ActiviteForm;
use Application\Form\FicheMetier\ActiviteExistanteForm;
use Application\Form\FicheMetier\ApplicationsForm;
use Application\Form\FicheMetier\FormationBaseForm;
use Application\Form\FicheMetier\FormationComportementaleForm;
use Application\Form\FicheMetier\FormationOperationnelleForm;
use Application\Form\FicheMetier\LibelleForm;
use Application\Form\FicheMetier\MissionsPrincipalesForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\FicheMetier\FicheMetierService;
use Application\Service\RessourceRh\RessourceRhService;
use Zend\Mvc\Controller\ControllerManager;

class FicheMetierControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var ActiviteService $activiteService
         * @var FicheMetierService $ficheMetierService
         * @var RessourceRhService $ressourceRhService
         */
        $activiteService = $manager->getServiceLocator()->get(ActiviteService::class);
        $ficheMetierService = $manager->getServiceLocator()->get(FicheMetierService::class);
        $ressourceRhService = $manager->getServiceLocator()->get(RessourceRhService::class);

        /**
         * @var ActiviteForm $activiteForm
         * @var ActiviteExistanteForm $activiteExistanteForm
         * @var ApplicationsForm $applicationsForm
         * @var FormationBaseForm $formationBaseForm
         * @var FormationComportementaleForm $formationComportementalForm
         * @var FormationOperationnelleForm $formationOperationnelleForm
         * @var LibelleForm $libelleForm
         * @var MissionsPrincipalesForm $missionsPrincipalesForm
         */
        $activiteForm = $manager->getServiceLocator()->get('FormElementManager')->get(ActiviteForm::class);
        $activiteExistanteForm = $manager->getServiceLocator()->get('FormElementManager')->get(ActiviteExistanteForm::class);
        $applicationsForm = $manager->getServiceLocator()->get('FormElementManager')->get(ApplicationsForm::class);
        $formationBaseForm = $manager->getServiceLocator()->get('FormElementManager')->get(FormationBaseForm::class);
        $formationComportementalForm = $manager->getServiceLocator()->get('FormElementManager')->get(FormationComportementaleForm::class);
        $formationOperationnelleForm = $manager->getServiceLocator()->get('FormElementManager')->get(FormationOperationnelleForm::class);
        $libelleForm = $manager->getServiceLocator()->get('FormElementManager')->get(LibelleForm::class);
        $missionsPrincipalesForm = $manager->getServiceLocator()->get('FormElementManager')->get(MissionsPrincipalesForm::class);


        /** @var FicheMetierController $controller */
        $controller = new FicheMetierController();

        $controller->setActiviteService($activiteService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setRessourceRhService($ressourceRhService);

        $controller->setActiviteForm($activiteForm);
        $controller->setActiviteExistanteForm($activiteExistanteForm);
        $controller->setApplicationsForm($applicationsForm);
        $controller->setFormationBaseForm($formationBaseForm);
        $controller->setFormationComportementaleForm($formationComportementalForm);
        $controller->setFormationOperationnelleForm($formationOperationnelleForm);
        $controller->setLibelleForm($libelleForm);
        $controller->setMissionsPrincipalesForm($missionsPrincipalesForm);


        return $controller;
    }

}