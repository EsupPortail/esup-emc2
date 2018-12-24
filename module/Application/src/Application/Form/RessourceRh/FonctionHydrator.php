<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\Fonction;
use Zend\Stdlib\Hydrator\HydratorInterface;

class FonctionHydrator implements HydratorInterface {

    /**
     * @param Fonction $object
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
     * @param Fonction $object
     * @return Fonction
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        return $object;
    }

}