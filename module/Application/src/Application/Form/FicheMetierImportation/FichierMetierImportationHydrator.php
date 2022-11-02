<?php

namespace Application\Form\FicheMetierImportation;

use Laminas\Hydrator\HydratorInterface;

class FichierMetierImportationHydrator implements HydratorInterface
{
    public function extract(object $object): array
    {
        return [];
    }

    public function hydrate(array $data, object $object)
    {
        return $object;
    }


}

