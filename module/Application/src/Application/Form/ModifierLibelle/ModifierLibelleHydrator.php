<?php

//TODO unicaen - JP

namespace Application\Form\ModifierLibelle;

use Laminas\Hydrator\HydratorInterface;

class ModifierLibelleHydrator implements HydratorInterface {

    /**
     * Object with getLibelle requiered
     * //TODO interface ...
     */

    /**
     * @param object $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'libelle' => html_entity_decode(strip_tags($object->getLibelle())),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param object $object
     * @return object|void
     */
    public function hydrate(array $data, $object)
    {
        $libelle = isset($data['libelle'])?$data['libelle']:null;
        $object->setLibelle($libelle);
        return $object;
    }


}