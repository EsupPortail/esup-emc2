<?php

namespace Application\Form\Poste;

use Application\Service\Agent\AgentService;
use Application\Service\Immobilier\ImmobilierService;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\Structure\StructureService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;

class PosteFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         * @var ImmobilierService $immobilierService
         * @var StructureService $structureService
         * @var RessourceRhService $ressourceService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService  = $container->get(AgentService::class);
        $immobilierService = $container->get(ImmobilierService::class);
        $structureService = $container->get(StructureService::class);
        $ressourceService  = $container->get(RessourceRhService::class);

        /** @var PosteHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(PosteHydrator::class);


        $form = new PosteForm();
        $form->setEntityManager($entityManager);
        $form->setAgentService($agentService);
        $form->setImmobilierService($immobilierService);
        $form->setStructureService($structureService);
        $form->setRessourceRhService($ressourceService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}