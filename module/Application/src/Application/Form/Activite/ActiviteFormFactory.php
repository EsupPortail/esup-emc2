<?php

namespace Application\Form\Activite;

use Application\Service\Application\ApplicationService;
use Zend\Form\FormElementManager;

class ActiviteFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /**
         * @var ApplicationService $applicationService
         * @var ActiviteHydrator $hydrator
         */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(ActiviteHydrator::class);
        $applicationService = $manager->getServiceLocator()->get(ApplicationService::class);


        $form = new ActiviteForm();
        $form->setApplicationService($applicationService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}