<?php

namespace Application\Form\ModifierLibelle;

use Laminas\Hydrator\HydratorInterface;

class ModifierLibelleHydrator implements HydratorInterface {

    public function extract(object $object): array
    {
        $data = [
            'libelle' => ($object AND $object->getLibelle())?html_entity_decode(strip_tags($object->getLibelle())):null,
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $libelle = $data['libelle'] ?? null;
        $object->setLibelle($libelle);
        return $object;
    }


}