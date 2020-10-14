<?php

namespace UnicaenEtat\Form\Etat;

use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class EtatFormFactory {

    /**
     * @param ContainerInterface $container
     * @return EtatForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var EtatTypeService $etatTypeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $etatTypeService = $container->get(EtatTypeService::class);

        /** @var EtatHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(EtatHydrator::class);

        $form = new EtatForm();
        $form->setEntityManager($entityManager);
        $form->setEtatTypeService($etatTypeService);
        $form->setHydrator($hydrator);

        return $form;
    }
}