<?php

namespace Application\Form\Agent;

use Zend\Stdlib\Hydrator\HydratorInterface;

class AssocierMissionSpecifiqueHydrator implements HydratorInterface {
    public function extract($object)
    {
        return [];
    }

    public function hydrate(array $data, $object)
    {
        return $object;
    }


}