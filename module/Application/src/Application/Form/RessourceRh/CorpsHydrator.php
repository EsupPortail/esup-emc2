<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\Corps;
use Zend\Stdlib\Hydrator\HydratorInterface;

class CorpsHydrator implements HydratorInterface {

    /**
     * @param Corps $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'code'      => $object->getCode(),
            'libelle'   => $object->getLibelle(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Corps $object
     * @return Corps
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode($data['code']);
        $object->setLibelle($data['libelle']);
        return $object;
    }

}