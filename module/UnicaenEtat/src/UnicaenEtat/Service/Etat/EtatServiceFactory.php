<?php

namespace UnicaenEtat\Service\Etat;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Laminas\View\Renderer\PhpRenderer;

class EtatServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return EtatService
     */
    public function __invoke(ContainerInterface $container) : EtatService
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        $service = new EtatService();
        $service->setRenderer($renderer);
        $service->setEntityManager($entityManager);
        return $service;
    }
}