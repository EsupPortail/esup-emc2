<?php

namespace Formation\Form\PlanDeFormationImportation;

use Formation\Entity\Db\PlanDeFormation;
use Laminas\Hydrator\HydratorInterface;

class PlanDeFormationImportationHydrator implements HydratorInterface
{
    public function extract(object $object): array
    {
        /** @var PlanDeFormation $object */
        return [
            'plan-de-formation' => ($object)?$object->getId():null,
        ];
    }

    public function hydrate(array $data, object $object) : object
    {
        return $object;
    }

}

