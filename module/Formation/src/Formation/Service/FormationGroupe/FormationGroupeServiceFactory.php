<?php

namespace Formation\Service\FormationGroupe;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\View\Renderer\PhpRenderer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationGroupeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationGroupeService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FormationGroupeService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /* @var PhpRenderer $renderer */
        $renderer = $container->get('ViewRenderer');

        /** @var FormationGroupeService $service */
        $service = new FormationGroupeService();
        $service->setEntityManager($entityManager);
        $service->setRenderer($renderer);
        return $service;
    }
}
