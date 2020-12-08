<?php

namespace UnicaenNote\Form\Type;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class TypeFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /**
         * @var TypeHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(TypeHydrator::class);

        $form = new TypeForm();
        $form->setEntityManager($entityManager);
        $form->setHydrator($hydrator);
        return $form;
    }
}