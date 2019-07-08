<?php 

namespace Application\Form\Application;

use Application\Service\Formation\FormationService;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class ApplicationHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /** @var FormationService $formationService */
        $formationService = $manager->getServiceLocator()->get(FormationService::class);

        /** @var ApplicationHydrator $hydrator */
        $hydrator = new ApplicationHydrator();
        $hydrator->setFormationService($formationService);
        return $hydrator;
    }
}