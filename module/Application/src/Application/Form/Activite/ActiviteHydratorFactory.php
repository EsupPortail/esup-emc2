<?php

namespace Application\Form\Activite;

use Application\Service\Application\ApplicationService;
use Zend\Form\FormElementManager;

class ActiviteHydratorFactory {

    public function __invoke(FormElementManager $manager)
    {
        /**
         * @var ApplicationService $applicationService
         */
        $applicationService = $manager->getServiceLocator()->get(ApplicationService::class);

        $hydrator = new ActiviteHydrator();
        $hydrator->setApplicationService($applicationService);

        return $hydrator;
    }
}