<?php

namespace Application\Form\ModifierLibelle;

use FicheMetier\Entity\Db\FicheMetier;
use Laminas\Hydrator\HydratorInterface;

class ModifierLibelleHydrator implements HydratorInterface {

    public function extract(object $object): array
    {
        /** @var FicheMetier $object */
        $libelle = $object?->getLibelle(false);
        $data = [
            'libelle' => ($libelle)?html_entity_decode(strip_tags($libelle)):null,
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