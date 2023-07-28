<?php

namespace Formation\Form\EnqueteReponse;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class EnqueteReponseHydratorFactory
{

    /**
     * @param ContainerInterface $container
     * @return EnqueteReponseHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EnqueteReponseHydrator
    {
        /**
         * @var EntityManager $entitymanager
         */
        $entitymanager = $container->get('doctrine.entitymanager.orm_default');

        $hydrator = new EnqueteReponseHydrator();
        $hydrator->setEntityManager($entitymanager);
        return $hydrator;
    }
}