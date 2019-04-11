<?php

namespace Application\Form\Structure;

use Application\Service\Structure\StructureService;
use Zend\Form\FormElementManager;

class StructureFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var StructureHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(StructureHydrator::class);

        /** @var StructureService $structureService */
        $structureService = $manager->getServiceLocator()->get(StructureService::class);

        $form = new StructureForm();
        $form->setStructureService($structureService);
        $form->setHydrator($hydrator);
        $form->init();
        return $form;
    }
}