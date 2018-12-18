<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\Correspondance;
use Zend\Stdlib\Hydrator\HydratorInterface;

class CorrespondanceHydrator implements HydratorInterface {

    /**
     * @param Correspondance $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'reference' => $object->getReference(),
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Correspondance $object
     * @return Correspondance
     */
    public function hydrate(array $data, $object)
    {
        $object->setReference($data['reference']);
        $object->setLibelle($data['libelle']);
        $object->setDescription($data['description']);
        return $object;
    }

}