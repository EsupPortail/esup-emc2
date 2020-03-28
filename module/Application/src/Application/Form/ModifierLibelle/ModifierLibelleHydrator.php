<?php

//TODO unicaen - JP

namespace Application\Form\ModifierLibelle;

use Zend\Hydrator\HydratorInterface;

class ModifierLibelleHydrator implements HydratorInterface {

    /**
     * Object with getLibelle requiered
     * //TODO interface ...
     */

    /**
     * @param object $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => strip_tags($object->getLibelle()),
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
        // never used
    }


}