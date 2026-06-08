<?php

namespace Carriere\Form\Specialite;

use Carriere\Service\CorrespondanceType\CorrespondanceTypeService;
use Psr\Container\ContainerInterface;

class SpecialiteHydratorFactory
{
    public function __invoke(ContainerInterface $container): SpecialiteHydrator
    {
        /**
         * @var CorrespondanceTypeService $correspondanceTypeService
         */
        $correspondanceTypeService = $container->get(CorrespondanceTypeService::class);

        $hydrator = new SpecialiteHydrator();
        $hydrator->setCorrespondanceTypeService($correspondanceTypeService);
        return $hydrator;
    }
}
