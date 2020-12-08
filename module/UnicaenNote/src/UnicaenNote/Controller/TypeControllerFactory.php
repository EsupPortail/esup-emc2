<?php

namespace UnicaenNote\Controller;

use Interop\Container\ContainerInterface;
use UnicaenNote\Form\Type\TypeForm;
use UnicaenNote\Service\Type\TypeService;

class TypeControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var TypeService $typeService
         */
        $typeService = $container->get(TypeService::class);

        /**
         * @var TypeForm $typeForm
         */
        $typeForm = $container->get('FormElementManager')->get(TypeForm::class);

        $controller = new TypeController();
        $controller->setTypeService($typeService);
        $controller->setTypeForm($typeForm);
        return $controller;
    }
}