<?php

namespace Application\Form\Formation;

use Application\Entity\Db\Formation;
use Zend\Stdlib\Hydrator\HydratorInterface;

class FormationHydrator implements HydratorInterface {

    /**
     * @var Formation $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'lien' => $object->getLien(),
        ];

        return $data;
    }

    /**
     * @param array $data
     * @param Formation $object
     * @return Formation
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setDescription($data['description']);
        $object->setLien($data['lien']);
        return $object;
    }


}