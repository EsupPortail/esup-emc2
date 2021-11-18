<?php

namespace Metier\Controller;

use Metier\Form\Domaine\DomaineForm;
use Metier\Service\Domaine\DomaineService;
use Interop\Container\ContainerInterface;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;

class DomaineControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return DomaineController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var DomaineService $domaineService
         * @var FamilleProfessionnelleService $familleProfestionnelService
         */
        $domaineService = $container->get(DomaineService::class);
        $familleProfestionnelService = $container->get(FamilleProfessionnelleService::class);

        /**
         * @var DomaineForm $domaineForm
         */
        $domaineForm = $container->get('FormElementManager')->get(DomaineForm::class);

        $controller = new DomaineController();
        $controller->setDomaineService($domaineService);
        $controller->setFamilleProfessionnelleService($familleProfestionnelService);
        $controller->setDomaineForm($domaineForm);
        return $controller;
    }
}