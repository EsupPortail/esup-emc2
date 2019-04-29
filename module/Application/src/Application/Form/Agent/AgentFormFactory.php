<?php

namespace Application\Form\Agent;

use Doctrine\ORM\EntityManager;
use Zend\Form\FormElementManager;

class AgentFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentHydrator $hydrator
         */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(AgentHydrator::class);
        $entityManager =  $manager->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        $form = new AgentForm();
        $form->setEntityManager($entityManager);
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}