<?php

namespace Carriere\Controller;

use Carriere\Form\FonctionType\FonctionTypeForm;
use Carriere\Service\FonctionType\FonctionTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class FonctionTypeControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FonctionTypeController
    {
        /**
         * @var FonctionTypeService $fonctionTypeService
         * @var FonctionTypeForm $fonctionTypeForm
         */
        $fonctionTypeService = $container->get(FonctionTypeService::class);
        $fonctionTypeForm = $container->get('FormElementManager')->get(FonctionTypeForm::class);

        $controller = new FonctionTypeController();
        $controller->setFonctionTypeService($fonctionTypeService);
        $controller->setFonctionTypeForm($fonctionTypeForm);
        return $controller;
    }

}
