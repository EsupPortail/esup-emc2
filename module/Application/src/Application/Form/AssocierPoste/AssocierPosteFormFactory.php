<?php

namespace Application\Form\AssocierPoste;

use Application\Service\Poste\PosteService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class AssocierPosteFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var AssocierPosteHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AssocierPosteHydrator::class);

        /**
         * @var EntityManager $entityManager
         * @var PosteService $posteService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $posteService = $container->get(PosteService::class);

        /** @var AssocierPosteForm $form */
        $form = new AssocierPosteForm();
        $form->setEntityManager($entityManager);
        $form->setPosteService($posteService);

        $form->setHydrator($hydrator);
        $form->init();


        return $form;
    }

}