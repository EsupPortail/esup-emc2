<?php

namespace Application\Form\Poste;

use Application\Service\Agent\AgentService;
use Application\Service\RessourceRh\RessourceRhService;
use Doctrine\ORM\EntityManager;
use Zend\Form\FormElementManager;

class PosteFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         * @var RessourceRhService $ressourceService
         */
        $entityManager = $manager->getServiceLocator()->get('doctrine.entitymanager.orm_default');
        $agentService  = $manager->getServiceLocator()->get(AgentService::class);
        $ressourceService  = $manager->getServiceLocator()->get(RessourceRhService::class);

        /** @var PosteHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(PosteHydrator::class);

        $form = new PosteForm();
        $form->setEntityManager($entityManager);
        $form->setAgentService($agentService);
        $form->setRessourceRhService($ressourceService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}