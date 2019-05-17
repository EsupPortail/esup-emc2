<?php

namespace Application\Controller\RessourceRh;

use Application\Form\MissionSpecifique\MissionSpecifiqueForm;
use Application\Form\RessourceRh\CorpsForm;
use Application\Form\RessourceRh\CorrespondanceForm;
use Application\Form\RessourceRh\DomaineForm;
use Application\Form\RessourceRh\GradeForm;
use Application\Form\RessourceRh\FamilleProfessionnelleForm;
use Application\Form\RessourceRh\MetierForm;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Application\Service\RessourceRh\RessourceRhService;
use Zend\Mvc\Controller\ControllerManager;

class RessourceRhControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var RessourceRhService $ressourceService
         *
         * @var FamilleProfessionnelleService $familleService
         */
        $ressourceService    = $manager->getServiceLocator()->get(RessourceRhService::class);
        $familleService      = $manager->getServiceLocator()->get(FamilleProfessionnelleService::class);

        /**
         * @var CorpsForm $corpsForm
         * @var CorrespondanceForm $correspondanceForm
         * @var DomaineForm $domaineForm
         * @var GradeForm $gradeForm
         * @var FamilleProfessionnelleForm $familleForm
         * @var MetierForm $metierForm
         * @var MissionSpecifiqueForm $missionSpecifiqueForm
         */
        $corpsForm                  = $manager->getServiceLocator()->get('FormElementManager')->get(CorpsForm::class);
        $correspondanceForm         = $manager->getServiceLocator()->get('FormElementManager')->get(CorrespondanceForm::class);
        $gradeForm                  = $manager->getServiceLocator()->get('FormElementManager')->get(GradeForm::class);
        $missionSpecifiqueForm      = $manager->getServiceLocator()->get('FormElementManager')->get(MissionSpecifiqueForm::class);

        $familleForm                = $manager->getServiceLocator()->get('FormElementManager')->get(FamilleProfessionnelleForm::class);
//        $domaineForm                = $manager->getServiceLocator()->get('FormElementManager')->get(DomaineForm::class);
//        $metierForm                 = $manager->getServiceLocator()->get('FormElementManager')->get(MetierForm::class);


        /** @var RessourceRhController $controller */
        $controller = new RessourceRhController();

        $controller->setRessourceRhService($ressourceService);
        $controller->setFamilleProfessionnelleService($familleService);

        $controller->setCorpsForm($corpsForm);
        $controller->setCorrespondanceForm($correspondanceForm);
        $controller->setGradeForm($gradeForm);
        $controller->setMissionSpecifiqueForm($missionSpecifiqueForm);
        $controller->setFamilleProfessionnelleForm($familleForm);

//        $controller->setDomaineForm($domaineForm);
//        $controller->setMetierForm($metierForm);

        return $controller;
    }

}