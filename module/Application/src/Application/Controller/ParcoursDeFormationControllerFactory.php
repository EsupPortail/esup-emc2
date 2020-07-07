<?php

namespace Application\Controller;

use Application\Form\ParcoursDeFormation\ParcoursDeFormationForm;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use Interop\Container\ContainerInterface;

class ParcoursDeFormationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return ParcoursDeFormationController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ParcoursDeFormationService $parcoursService
         */
        $parcoursService = $container->get(ParcoursDeFormationService::class);

        /**
         * @var ParcoursDeFormationForm $parcoursForm
         */
        $parcoursForm = $container->get('FormElementManager')->get(ParcoursDeFormationForm::class);

        /** @var ParcoursDeFormationController $controller */
        $controller = new ParcoursDeFormationController();
        $controller->setParcoursDeFormationService($parcoursService);
        $controller->setParcoursDeFormationForm($parcoursForm);
        return $controller;
    }
}