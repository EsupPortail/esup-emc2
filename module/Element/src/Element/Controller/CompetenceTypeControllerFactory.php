<?php

namespace Element\Controller;

use Element\Form\CompetenceType\CompetenceTypeForm;
use Element\Service\CompetenceType\CompetenceTypeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceTypeControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceTypeController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceTypeController
    {
        /**
         * @var CompetenceTypeService $competenceTypeService
         * @var CompetenceTypeForm $competenceTypeForm
         */
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $competenceTypeForm = $container->get('FormElementManager')->get(CompetenceTypeForm::class);

        $controller = new CompetenceTypeController();
        $controller->setCompetenceTypeService($competenceTypeService);
        $controller->setCompetenceTypeForm($competenceTypeForm);
        return $controller;
    }
}