<?php

namespace Application\Form\AssocierPoste;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class AssocierPosteFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var AssocierPosteHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AssocierPosteHydrator::class);

        /** @var EntityManager $entityManager **/
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var AssocierPosteForm $form */
        $form = new AssocierPosteForm();
        $form->setEntityManager($entityManager);

        $form->setHydrator($hydrator);
        $form->init();


        return $form;
    }

}