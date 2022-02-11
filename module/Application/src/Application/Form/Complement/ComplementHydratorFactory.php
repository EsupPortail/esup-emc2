<?php

namespace Application\Form\Complement;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ComplementHydratorFactory {

    public function __invoke(ContainerInterface $container) : ComplementHydrator
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        $hydrator = new ComplementHydrator();
        $hydrator->setEntityManager($entityManager);
        return $hydrator;
    }
}