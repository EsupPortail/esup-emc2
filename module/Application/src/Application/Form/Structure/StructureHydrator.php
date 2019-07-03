<?php

namespace Application\Form\Structure;

use Application\Entity\Db\Structure;
use Zend\Stdlib\Hydrator\HydratorInterface;

class StructureHydrator implements HydratorInterface {

    /**
     * @param Structure $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    public function hydrate(array $data, $object)
    {
        if ($data['description'] && $data['description'] != '') $object->setDescription($data['description']);
        return $object;
    }


}