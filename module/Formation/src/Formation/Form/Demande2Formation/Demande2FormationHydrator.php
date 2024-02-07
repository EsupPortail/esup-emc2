<?php

namespace Formation\Form\Demande2Formation;

use Formation\Entity\Db\DemandeExterne;
use Laminas\Hydrator\HydratorInterface;

class Demande2FormationHydrator implements HydratorInterface {

    public function extract(object $object): array
    {
        /** @var DemandeExterne $object */
        $data = [
            'libelle' => $object->getLibelle(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        /** @var DemandeExterne $object */
        return $object;
    }


}