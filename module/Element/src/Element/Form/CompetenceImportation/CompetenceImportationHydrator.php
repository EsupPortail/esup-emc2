<?php

namespace Element\Form\CompetenceImportation;

use Laminas\Hydrator\HydratorInterface;

class CompetenceImportationHydrator implements HydratorInterface
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

