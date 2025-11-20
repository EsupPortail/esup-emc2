<?php

namespace Metier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Carriere\Service\Correspondance\CorrespondanceService;
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
         */
        $correspondanceService = $container->get(CorrespondanceService::class);
        $familleService = $container->get(FamilleProfessionnelleService::class);

        /**
         * @var FamilleProfessionnelleForm $familleProfessionnelleForm
         */
        $familleProfessionnelleForm = $container->get('FormElementManager')->get(FamilleProfessionnelleForm::class);

        $controller = new FamilleProfessionnelleController();
        $controller->setCorrespondanceService($correspondanceService);
        $controller->setFamilleProfessionnelleService($familleService);
        $controller->setFamilleProfessionnelleForm($familleProfessionnelleForm);
        return $controller;
    }
}