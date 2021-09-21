<?php

namespace UnicaenDocument\Form\Macro;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class MacroFormFactory {

    /**
     * @param ContainerInterface $container
     * @return MacroForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');

        /**
         * @var MacroHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(MacroHydrator::class);

        $form = new MacroForm();
        $form->setEntityManager($entityManager);
        $form->setHydrator($hydrator);
        return $form;
    }
}