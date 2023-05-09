<?php

namespace Application\Form\AgentHierarchieSaisie;

use Laminas\Hydrator\HydratorInterface;

class AgentHierarchieSaisieHydrator implements HydratorInterface {

    public function extract(object $object) : array
    {
        return [];
    }

    public function hydrate(array $data, object $object) : object
    {
        return $object;
    }
}