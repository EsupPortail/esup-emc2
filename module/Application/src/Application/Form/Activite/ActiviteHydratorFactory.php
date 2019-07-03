<?php

namespace Application\Form\Activite;

use Application\Service\Application\ApplicationService;
use Zend\Form\FormElementManager;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class ActiviteHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
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