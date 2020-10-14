<?php

namespace UnicaenEtat\Form\EtatType;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class EtatTypeFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $hydrator = $container->get('HydratorManager')->get(EtatTypeHydrator::class);

        $form = new EtatTypeForm();
        $form->setEntityManager($entityManager);
        $form->setHydrator($hydrator);
        return $form;
    }
}