<?php

namespace Application\Form\FicheMetierType;

use Application\Service\Application\ApplicationService;
use Zend\Form\FormElementManager;

class ApplicationsFormFactory{

    public function __invoke(FormElementManager $manager)
    {
        /** @var ApplicationService $applicationService*/
        $applicationService = $manager->getServiceLocator()->get(ApplicationService::class);

        /** @var ApplicationsHydrator $hydrator */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(ApplicationsHydrator::class);

        $form = new ApplicationsForm();
        $form->setApplicationService($applicationService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}