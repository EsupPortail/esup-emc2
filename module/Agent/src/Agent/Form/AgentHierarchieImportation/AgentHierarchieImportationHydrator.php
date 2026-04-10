<?php

namespace Agent\Form\AgentHierarchieImportation;

use Laminas\Hydrator\HydratorInterface;

class AgentHierarchieImportationHydrator implements HydratorInterface
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

