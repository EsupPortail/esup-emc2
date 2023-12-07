<?php

namespace Formation\Controller;


use Formation\Form\StagiaireExterne\StagiaireExterneForm;
use Formation\Service\StagiaireExterne\StagiaireExterneService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class StagiaireExterneControllerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): StagiaireExterneController
    {
        /**
         * @var StagiaireExterneService $stagiaireExterneService
         * @var StagiaireExterneForm $stagiaireExterneForm
         */
        $stagiaireExterneService = $container->get(StagiaireExterneService::class);
        $stagiaireExterneForm = $container->get('FormElementManager')->get(StagiaireExterneForm::class);

        $controller = new StagiaireExterneController();
        $controller->setStagiaireExterneService($stagiaireExterneService);
        $controller->setStagiaireExterneForm($stagiaireExterneForm);
        return $controller;
    }

}