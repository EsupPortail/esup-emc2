<?php

namespace UnicaenDocument\Form\Contenu;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenDocument\Service\Macro\MacroService;

class ContenuFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ContenuForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var MacroService $macroService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $macroService = $container->get(MacroService::class);

        /** @var ContenuHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ContenuHydrator::class);

        $form = new ContenuForm();
        $form->setEntityManager($entityManager);
        $form->setMacroService($macroService);
        $form->setHydrator($hydrator);
        return $form;
    }
}