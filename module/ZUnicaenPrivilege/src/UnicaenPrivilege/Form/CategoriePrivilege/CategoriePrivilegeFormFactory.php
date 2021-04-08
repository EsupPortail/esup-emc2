<?php

namespace UnicaenPrivilege\Form\CategoriePrivilege;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class CategoriePrivilegeFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entitymanager
         */
        $entitymanager = $container->get('doctrine.entitymanager.orm_default');

        /** @var CategoriePrivilegeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CategoriePrivilegeHydrator::class);

        /** @var CategoriePrivilegeForm $form */
        $form = new CategoriePrivilegeForm();
        $form->setEntityManager($entitymanager);
        $form->setHydrator($hydrator);
        return $form;
    }
}