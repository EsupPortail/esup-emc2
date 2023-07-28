<?php

namespace Formation\Form\EnqueteReponse;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class EnqueteReponseFormFactory {

    /**
     * @param ContainerInterface $container
     * @return EnqueteReponseForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EnqueteReponseForm
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var EnqueteReponseHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(EnqueteReponseHydrator::class);

        $form = new EnqueteReponseForm();
        $form->setEntityManager($entityManager);
        $form->setHydrator($hydrator);
        return $form;
    }
}