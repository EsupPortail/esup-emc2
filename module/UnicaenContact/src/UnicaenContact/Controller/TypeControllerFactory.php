<?php

namespace UnicaenContact\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenContact\Form\Type\TypeForm;
use UnicaenContact\Service\Contact\ContactService;
use UnicaenContact\Service\Type\TypeService;

class TypeControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): TypeController
    {
        /**
         * @var ContactService $contactService
         * @var TypeService $typeService
         * @var TypeForm $typeForm
         */
        $contactService = $container->get(ContactService::class);
        $typeService = $container->get(TypeService::class);
        $typeForm = $container->get('FormElementManager')->get(TypeForm::class);

        $controller = new TypeController();
        $controller->setContactService($contactService);
        $controller->setTypeService($typeService);
        $controller->setTypeForm($typeForm);
        return $controller;
    }
}
