<?php

namespace Formation\Service\FormationGroupe;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Zend\View\Renderer\PhpRenderer;

class FormationGroupeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationGroupeService
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
