<?php

namespace UnicaenNote\Service\PorteNote;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class PorteNoteServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return PorteNoteService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entitymanager
         */
        $entitymanager = $container->get('doctrine.entitymanager.orm_default');

        $service = new PorteNoteService();
        $service->setEntityManager($entitymanager);
        return $service;
    }

}