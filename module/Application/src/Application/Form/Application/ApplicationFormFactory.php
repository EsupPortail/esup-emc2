<?php

namespace Application\Form\Application;

use Application\Service\Formation\FormationService;
use Zend\Form\FormElementManager;

class ApplicationFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /** @var FormationService $formationService */
        $formationService = $manager->getServiceLocator()->get(FormationService::class);

        /** @var ApplicationHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(ApplicationHydrator::class);

        $form = new ApplicationForm();
        $form->setFormationService($formationService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}