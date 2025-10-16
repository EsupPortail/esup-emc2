<?php

namespace Structure\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenContact\Form\Contact\ContactForm;
use UnicaenContact\Service\Contact\ContactService;

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
         */
        $contactService = $container->get(ContactService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var ContactForm $contactForm
         */
        $contactForm = $container->get('FormElementManager')->get(ContactForm::class);

        $controller = new ContactController();
        $controller->setContactService($contactService);
        $controller->setStructureService($structureService);
        $controller->setContactForm($contactForm);
        return $controller;
    }
}