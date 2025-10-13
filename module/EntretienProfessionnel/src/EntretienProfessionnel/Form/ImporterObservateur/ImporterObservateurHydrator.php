<?php

namespace EntretienProfessionnel\Form\ImporterObservateur;

use Laminas\Hydrator\HydratorInterface;

class ImporterObservateurHydrator implements HydratorInterface
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

