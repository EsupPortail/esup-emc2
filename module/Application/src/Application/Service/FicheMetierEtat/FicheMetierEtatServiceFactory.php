<?php

namespace Application\Service\FicheMetierEtat;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\View\Renderer\PhpRenderer;

class FicheMetierEtatServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierEtatService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var FicheMetierEtatService $service */
        $service = new FicheMetierEtatService();
        $service->setEntityManager($entityManager);
        $service->setRenderer($renderer);
        return $service;
    }
}