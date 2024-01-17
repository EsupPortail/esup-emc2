<?php

namespace Formation\Controller;

use Formation\Form\Domaine\DomaineForm;
use Formation\Form\SelectionFormation\SelectionFormationForm;
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
         * @var SelectionFormationForm $selectionFormationForm
         */
        $domaineService = $container->get(DomaineService::class);
        $domaineForm = $container->get('FormElementManager')->get(DomaineForm::class);
        $selectionFormationForm = $container->get('FormElementManager')->get(SelectionFormationForm::class);

        $controller = new DomaineController();
        $controller->setDomaineService($domaineService);
        $controller->setDomaineForm($domaineForm);
        $controller->setSelectionFormationForm($selectionFormationForm);
        return $controller;
    }
}