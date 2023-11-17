<?php

namespace Formation\Controller;

use Formation\Form\Axe\AxeForm;
use Formation\Service\Axe\AxeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class AxeControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return AxeController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AxeController
    {
        /**
         * @var AxeService $axeService
         * @var AxeForm $axeForm
         */
        $axeService = $container->get(AxeService::class);
        $axeForm = $container->get('FormElementManager')->get(AxeForm::class);

        $controller = new AxeController();
        $controller->setAxeService($axeService);
        $controller->setAxeForm($axeForm);
        return $controller;
    }
}