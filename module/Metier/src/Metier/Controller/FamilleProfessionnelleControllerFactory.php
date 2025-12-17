<?php

namespace Metier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Carriere\Service\Correspondance\CorrespondanceService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use Interop\Container\ContainerInterface;
use Metier\Form\FamilleProfessionnelle\FamilleProfessionnelleForm;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FamilleProfessionnelleControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FamilleProfessionnelleController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FamilleProfessionnelleController
    {
        /**
         * @var CorrespondanceService $correspondanceService
         * @var FamilleProfessionnelleService $familleService
         * @var FicheMetierService $ficheMetierService
         * @var MissionPrincipaleService $missionPrincipaleService
         */
        $correspondanceService = $container->get(CorrespondanceService::class);
        $familleService = $container->get(FamilleProfessionnelleService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);

        /**
         * @var FamilleProfessionnelleForm $familleProfessionnelleForm
         */
        $familleProfessionnelleForm = $container->get('FormElementManager')->get(FamilleProfessionnelleForm::class);

        $controller = new FamilleProfessionnelleController();
        $controller->setCorrespondanceService($correspondanceService);
        $controller->setFamilleProfessionnelleService($familleService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setFamilleProfessionnelleForm($familleProfessionnelleForm);
        return $controller;
    }
}