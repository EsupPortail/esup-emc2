<?php

namespace Application\Form\Activite;

use Application\Entity\Db\Activite;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ActiviteHydrator implements HydratorInterface {

    /**
     * @param Activite $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Activite $object
     * @return Activite
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setDescription($data['description']);
        return $object;
    }

}