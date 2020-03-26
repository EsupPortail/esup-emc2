<?php

namespace UnicaenUtilisateur\Form\User;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class UserFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /** @var UserHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(UserHydrator::class);
        
        /** @var UserForm $form */
        $form = new UserForm();
        $form->setEntityManager($entityManager);
        $form->setHydrator($hydrator);
        return $form;
    }
}