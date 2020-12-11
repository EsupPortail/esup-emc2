<?php

namespace UnicaenNote\Form\PorteNote;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class PorteNoteFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /**
         * @var PorteNoteHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(PorteNoteHydrator::class);

        $form = new PorteNoteForm();
        $form->setEntityManager($entityManager);
        $form->setHydrator($hydrator);
        return $form;
    }
}