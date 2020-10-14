<?php

namespace UnicaenEtat\Form\Action;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class ActionFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $hydrator = $container->get('HydratorManager')->get(ActionHydrator::class);

        $form = new ActionForm();
        $form->setEntityManager($entityManager);
        $form->setHydrator($hydrator);
        return $form;
    }
}