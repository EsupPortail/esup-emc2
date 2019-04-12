<?php

namespace Application\Form\AssocierPoste;

use Doctrine\ORM\EntityManager;
use Zend\Form\FormElementManager;

class AssocierPosteFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var AssocierPosteHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(AssocierPosteHydrator::class);

        /** @var EntityManager $entityManager **/
        $entityManager = $manager->getServiceLocator()->get('doctrine.entitymanager.orm_default');

        /** @var AssocierPosteForm $form */
        $form = new AssocierPosteForm();
        $form->setEntityManager($entityManager);

        $form->setHydrator($hydrator);
        $form->init();


        return $form;
    }

}