<?php

namespace Application\Form\Affectation;

use Application\Entity\Db\Affectation;
use Zend\Stdlib\Hydrator\HydratorInterface;

class AffectationHydrator implements HydratorInterface {

    /**
     * @param Affectation $object
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
     * @param Affectation $object
     * @return Affectation
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        if ($data['description'] === null || $data['description'] === '') {
            $object->setDescription(null);
        } else {
            $object->setDescription($data['description']);
        }
        return $object;
    }

}