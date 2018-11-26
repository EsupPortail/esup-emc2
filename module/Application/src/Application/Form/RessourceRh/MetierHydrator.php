<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\Metier;
use Zend\Stdlib\Hydrator\HydratorInterface;

class MetierHydrator implements HydratorInterface {

    /**
     * @param Metier $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Metier $object
     * @return Metier
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        return $object;
    }

}