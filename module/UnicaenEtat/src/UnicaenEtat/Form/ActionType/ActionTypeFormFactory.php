<?php

namespace UnicaenEtat\Form\ActionType;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ActionTypeFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $hydrator = $container->get('HydratorManager')->get(ActionTypeHydrator::class);

        $form = new ActionTypeForm();
        $form->setEntityManager($entityManager);
        $form->setHydrator($hydrator);
        return $form;
    }
}