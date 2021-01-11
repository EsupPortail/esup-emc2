<?php

namespace Application\Controller;

use Application\Form\Domaine\DomaineForm;
use Application\Service\Domaine\DomaineService;
use Interop\Container\ContainerInterface;

class DomaineControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return DomaineController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var DomaineService $domaineService
         */
        $domaineService = $container->get(DomaineService::class);

        /**
         * @var DomaineForm $domaineForm
         */
        $domaineForm = $container->get('FormElementManager')->get(DomaineForm::class);

        $controller = new DomaineController();
        $controller->setDomaineService($domaineService);
        $controller->setDomaineForm($domaineForm);
        return $controller;
    }
}