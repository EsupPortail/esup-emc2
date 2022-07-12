<?php

namespace Application\Form\AjouterFormation;

use Laminas\Hydrator\HydratorInterface;

class AjouterFormationHydrator implements HydratorInterface {

    public function extract($object): array
    {
        return [];
    }

    public function hydrate(array $data, $object)
    {
        return $object;
    }

}