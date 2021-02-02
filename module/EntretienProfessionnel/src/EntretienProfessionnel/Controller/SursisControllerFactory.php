<?php

namespace EntretienProfessionnel\Controller;

use EntretienProfessionnel\Form\Sursis\SursisForm;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Sursis\SursisService;
use Interop\Container\ContainerInterface;

class SursisControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntretienProfessionnelService $entretienService
         * @var SursisService $sursisService
         */
        $entretienService = $container->get(EntretienProfessionnelService::class);
        $sursisService = $container->get(SursisService::class);

        /**
         * @var SursisForm $sursisForm
         */
        $sursisForm = $container->get('FormElementManager')->get(SursisForm::class);

        $controller = new SursisController();
        $controller->setEntretienProfessionnelService($entretienService);
        $controller->setSursisService($sursisService);
        $controller->setSursisForm($sursisForm);
        return $controller;
    }
}