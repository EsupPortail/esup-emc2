<?php

namespace Application\Service\Application;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\View\Renderer\PhpRenderer;

class ApplicationGroupeServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationGroupeService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var ApplicationGroupeService $service */
        $service = new ApplicationGroupeService();
        $service->setEntityManager($entityManager);
        $service->setRenderer($renderer);
        return $service;
    }
}
