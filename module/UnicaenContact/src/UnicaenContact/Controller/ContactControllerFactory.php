<?php

namespace UnicaenContact\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenContact\Form\Contact\ContactForm;
use UnicaenContact\Service\Contact\ContactService;

class ContactControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ContactController
    {
        /**
         * @var ContactService $contactService
         * @var ContactForm $contactForm
         */
        $contactService = $container->get(ContactService::class);
        $contactForm = $container->get('FormElementManager')->get(ContactForm::class);

        $controller = new ContactController();
        $controller->setContactForm($contactForm);
        $controller->setContactService($contactService);
        return $controller;
    }
}