<?php

namespace Formation\Form\SelectionPlanDeFormation;

use Laminas\Hydrator\HydratorInterface;

class SelectionPlanDeFormationHydrator implements HydratorInterface
{

    /**
     * @param $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [];
        return $data;
    }

    public function hydrate(array $data, $object): object
    {
        return $object;
    }
}