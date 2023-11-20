<?php

namespace Formation\Form\PlanDeFormationImportation;

use Laminas\Hydrator\HydratorInterface;

class PlanDeFormationImportationHydrator implements HydratorInterface
{
    public function extract(object $object): array
    {
        return [];
    }

    public function hydrate(array $data, object $object) : object
    {
        return $object;
    }

}

