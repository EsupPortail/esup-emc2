<?php

namespace FicheReferentiel\Form\Importation;

use Laminas\Hydrator\HydratorInterface;

class ImportationHydrator implements HydratorInterface
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

