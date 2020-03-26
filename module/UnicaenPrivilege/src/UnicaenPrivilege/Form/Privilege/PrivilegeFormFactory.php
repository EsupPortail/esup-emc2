<?php 

namespace UnicaenPrivilege\Form\Privilege;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class PrivilegeFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entitymanager
         */
        $entitymanager = $container->get('doctrine.entitymanager.orm_default');

        /** @var PrivilegeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(PrivilegeHydrator::class);

        /** @var PrivilegeForm $form */
        $form = new PrivilegeForm();
        $form->setEntityManager($entitymanager);
        $form->setHydrator($hydrator);
        return $form;
    }
}