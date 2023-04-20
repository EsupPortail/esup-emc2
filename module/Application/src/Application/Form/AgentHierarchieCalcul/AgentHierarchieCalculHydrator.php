<?php

namespace Application\Form\AgentHierarchieCalcul;

use Laminas\Hydrator\HydratorInterface;

class AgentHierarchieCalculHydrator implements HydratorInterface
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

