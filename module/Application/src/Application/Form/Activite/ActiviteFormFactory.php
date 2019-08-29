<?php

namespace Application\Form\Activite;

use Application\Service\Application\ApplicationService;
use Application\Service\Formation\FormationService;
use Zend\Form\FormElementManager;

class ActiviteFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /**
         * @var ApplicationService $applicationService
         * @var FormationService $formationService
         * @var ActiviteHydrator $hydrator
         */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(ActiviteHydrator::class);
        $applicationService = $manager->getServiceLocator()->get(ApplicationService::class);
        $formationService = $manager->getServiceLocator()->get(FormationService::class);


        $form = new ActiviteForm();
        $form->setApplicationService($applicationService);
        $form->setFormationService($formationService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}