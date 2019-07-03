<?php

namespace Application\Form\Structure;

use Zend\Form\FormElementManager;

class StructureFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var StructureHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(StructureHydrator::class);

        /** @var StructureForm $form */
        $form = new StructureForm();
        $form->setHydrator($hydrator);
        $form->init();
        return $form;
    }

}