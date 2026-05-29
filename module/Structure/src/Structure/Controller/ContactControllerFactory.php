<?php

namespace Structure\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use Structure\Form\Contact\ContactForm;
use UnicaenContact\Service\Contact\ContactService;
use UnicaenContact\Service\Type\TypeService;

class ContactControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ContactController
    {
        /**
         * @var ContactService $contactService
         * @var StructureService $structureService
         * @var TypeService $typeService
         */
        $contactService = $container->get(ContactService::class);
        $structureService = $container->get(StructureService::class);
        $typeService = $container->get(TypeService::class);

        /**
         * @var ContactForm $contactForm
         */
        $contactForm = $container->get('FormElementManager')->get(ContactForm::class);

        $controller = new ContactController();
        $controller->setContactService($contactService);
        $controller->setStructureService($structureService);
        $controller->setTypeService($typeService);
        $controller->setContactForm($contactForm);
        return $controller;
    }
}