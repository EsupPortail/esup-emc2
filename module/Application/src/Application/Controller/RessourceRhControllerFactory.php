<?php

namespace Application\Controller;

use Application\Form\MissionSpecifique\MissionSpecifiqueForm;
use Application\Form\RessourceRh\DomaineForm;
use Application\Form\RessourceRh\FonctionForm;
use Application\Form\RessourceRh\FamilleProfessionnelleForm;
use Application\Form\RessourceRh\MetierForm;
use Application\Form\RessourceRh\MissionSpecifiqueThemeForm;
use Application\Form\RessourceRh\MissionSpecifiqueTypeForm;
use Application\Service\Domaine\DomaineService;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Application\Service\Fonction\FonctionService;
use Application\Service\Metier\MetierService;
use Application\Service\RessourceRh\RessourceRhService;
use Interop\Container\ContainerInterface;

class RessourceRhControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var RessourceRhService $ressourceService
         *
         * @var DomaineService $domaineService
         * @var FamilleProfessionnelleService $familleService
         * @var FonctionService $fonctionService
         * @var MetierService $metierService
         */
        $ressourceService    = $container->get(RessourceRhService::class);
        $domaineService      = $container->get(DomaineService::class);
        $familleService      = $container->get(FamilleProfessionnelleService::class);
        $fonctionService     = $container->get(FonctionService::class);
        $metierService       = $container->get(MetierService::class);

        /**
         * @var DomaineForm $domaineForm
         * @var FamilleProfessionnelleForm $familleForm
         * @var FonctionForm $fonctionForm
         * @var MetierForm $metierForm
         * @var MissionSpecifiqueForm $missionSpecifiqueForm
         * @var MissionSpecifiqueTypeForm $missionSpecifiqueTypeForm
         */
        $missionSpecifiqueForm      = $container->get('FormElementManager')->get(MissionSpecifiqueForm::class);
        $missionSpecifiqueTypeForm  = $container->get('FormElementManager')->get(MissionSpecifiqueTypeForm::class);
        $missionSpecifiqueThemeForm = $container->get('FormElementManager')->get(MissionSpecifiqueThemeForm::class);

        $familleForm                = $container->get('FormElementManager')->get(FamilleProfessionnelleForm::class);
        $fonctionForm               = $container->get('FormElementManager')->get(FonctionForm::class);
        $domaineForm                = $container->get('FormElementManager')->get(DomaineForm::class);
        $metierForm                 = $container->get('FormElementManager')->get(MetierForm::class);


        /** @var RessourceRhController $controller */
        $controller = new RessourceRhController();

        $controller->setRessourceRhService($ressourceService);
        $controller->setDomaineService($domaineService);
        $controller->setFamilleProfessionnelleService($familleService);
        $controller->setFonctionService($fonctionService);
        $controller->setMetierService($metierService);

        $controller->setMissionSpecifiqueForm($missionSpecifiqueForm);
        $controller->setMissionSpecifiqueTypeForm($missionSpecifiqueTypeForm);
        $controller->setMissionSpecifiqueThemeForm($missionSpecifiqueThemeForm);
        $controller->setFamilleProfessionnelleForm($familleForm);
        $controller->setFonctionForm($fonctionForm);
        $controller->setDomaineForm($domaineForm);
        $controller->setMetierForm($metierForm);

        return $controller;
    }

}