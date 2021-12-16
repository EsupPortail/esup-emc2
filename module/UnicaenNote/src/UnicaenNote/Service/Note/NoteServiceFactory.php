<?php

namespace UnicaenNote\Service\Note;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class NoteServiceFactory {

    /**
     * @param ContainerInterface $container
     * @return NoteService
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entitymanager
         */
        $entitymanager = $container->get('doctrine.entitymanager.orm_default');

        $service = new NoteService();
        $service->setEntityManager($entitymanager);
        return $service;
    }

}