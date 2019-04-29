<?php

namespace Application\Controller\FicheMetier;

use Application\Form\Activite\ActiviteForm;
use Application\Form\FicheMetierType\ActiviteExistanteForm;
use Application\Form\FicheMetierType\ApplicationsForm;
use Application\Form\FicheMetierType\FormationBaseForm;
use Application\Form\FicheMetierType\FormationComportementaleForm;
use Application\Form\FicheMetierType\FormationOperationnelleForm;
use Application\Form\FicheMetierType\LibelleForm;
use Application\Form\FicheMetierType\MissionsPrincipalesForm;
use Application\Service\Activite\ActiviteService;
use Application\Service\FicheMetier\FicheMetierService;
use Zend\Mvc\Controller\ControllerManager;

class FicheMetierTypeControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var ActiviteService $activiteService
         * @var FicheMetierService $ficheMetierService
         */
        $activiteService = $manager->getServiceLocator()->get(ActiviteService::class);
        $ficheMetierService = $manager->getServiceLocator()->get(FicheMetierService::class);

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


        /** @var FicheMetierTypeController $controller */
        $controller = new FicheMetierTypeController();

        $controller->setActiviteService($activiteService);
        $controller->setFicheMetierService($ficheMetierService);

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