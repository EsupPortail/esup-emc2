<?php

namespace Application\Form\Activite;

use Application\Service\Application\ApplicationService;
use Application\Service\Formation\FormationService;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class ActiviteHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /**
         * @var ApplicationService $applicationService
         * @var FormationService $formationService
         */
        $applicationService = $manager->getServiceLocator()->get(ApplicationService::class);
        $formationService = $manager->getServiceLocator()->get(FormationService::class);

        $hydrator = new ActiviteHydrator();
        $hydrator->setApplicationService($applicationService);
        $hydrator->setFormationService($formationService);

        return $hydrator;
    }
}