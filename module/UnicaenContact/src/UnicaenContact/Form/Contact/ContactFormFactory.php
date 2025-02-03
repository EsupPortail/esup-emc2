<?php

namespace UnicaenContact\Form\Contact;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenContact\Service\Type\TypeService;

class ContactFormFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ContactForm
    {
        /**
         * @var TypeService $typeService
         * @var ContactHydrator $hydrator
         */
        $typeService = $container->get(TypeService::class);
        $hydrator = $container->get('HydratorManager')->get(ContactHydrator::class);

        $form = new ContactForm();
        $form->setHydrator($hydrator);
        $form->setTypeService($typeService);
        return $form;
    }

}