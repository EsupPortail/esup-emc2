<?php

//TODO unicaen - JP

namespace Application\Form\ModifierDescription;

use Zend\Hydrator\HydratorInterface;

class ModifierDescriptionHydrator implements HydratorInterface {

    /**
     * Object with getDescription requiered
     * //TODO interface ...
     */

    /**
     * @param object $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'description' => strip_tags($object->getDescription()),
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