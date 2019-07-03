<?php

namespace Application\Form\Poste;

use Application\Service\Agent\AgentService;
use Application\Service\Immobilier\ImmobilierService;
use Application\Service\RessourceRh\RessourceRhService;
use Application\Service\Structure\StructureService;
use Doctrine\ORM\EntityManager;
use Zend\Form\FormElementManager;

class PosteFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         * @var ImmobilierService $immobilierService
         * @var StructureService $structureService
         * @var RessourceRhService $ressourceService
         */
        $entityManager = $manager->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $agentService  = $manager->getServiceLocator()->get(AgentService::class);
        $immobilierService = $manager->getServiceLocator()->get(ImmobilierService::class);
        $structureService = $manager->getServiceLocator()->get(StructureService::class);
        $ressourceService  = $manager->getServiceLocator()->get(RessourceRhService::class);

        /** @var PosteHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(PosteHydrator::class);


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