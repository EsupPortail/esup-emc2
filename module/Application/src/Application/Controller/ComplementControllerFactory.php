<?php

namespace Application\Controller;

use Application\Form\Complement\ComplementForm;
use Application\Service\Complement\ComplementService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ComplementControllerFactory {

    public function __invoke(ContainerInterface $container) : ComplementController
    {
        /**
         * @var EntityManager $entityManager
         * @var ComplementService $complementService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $complementService = $container->get(ComplementService::class);

        /**
         * @var ComplementForm $complementForm
         */
        $complementForm = $container->get('FormElementManager')->get(ComplementForm::class);

        $controller = new ComplementController();
        $controller->setEntityManager($entityManager);
        $controller->setComplementService($complementService);
        $controller->setComplementForm($complementForm);
        return $controller;
    }
}