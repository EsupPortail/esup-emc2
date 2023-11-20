<?php

namespace Formation\Controller;

use Formation\Form\Domaine\DomaineForm;
use Formation\Service\Domaine\DomaineService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class DomaineControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return DomaineController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DomaineController
    {
        /**
         * @var DomaineService $domaineService
         * @var DomaineForm $domaineForm
         */
        $domaineService = $container->get(DomaineService::class);
        $domaineForm = $container->get('FormElementManager')->get(DomaineForm::class);

        $controller = new DomaineController();
        $controller->setDomaineService($domaineService);
        $controller->setDomaineForm($domaineForm);
        return $controller;
    }
}