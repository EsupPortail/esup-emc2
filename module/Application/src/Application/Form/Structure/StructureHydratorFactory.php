<?php

namespace Application\Form\Structure;

use Application\Service\Structure\StructureService;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class StructureHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /**
         * @var StructureService $structureService
         */
        $structureService = $manager->getServiceLocator()->get(StructureService::class);

        /**
         * @var StructureHydrator $hydrator
         */
        $hydrator = new StructureHydrator();
        $hydrator->setStructureService($structureService);
        return $hydrator;
    }

}