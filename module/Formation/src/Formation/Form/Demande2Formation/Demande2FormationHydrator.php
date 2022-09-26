<?php

namespace Formation\Form\Demande2Formation;

use Formation\Entity\Db\DemandeExterne;
use Laminas\Hydrator\HydratorInterface;

class Demande2FormationHydrator implements HydratorInterface {

    /**
     * @param DemandeExterne $object
     * @return array
     */
    public function extract(object $object): array
    {
        $data = [
            'libelle' => $object->getLibelle(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object)
    {
        return $object;
    }


}