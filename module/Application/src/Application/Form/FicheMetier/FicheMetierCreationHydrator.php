<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Zend\Stdlib\Hydrator\HydratorInterface;

class FicheMetierCreationHydrator implements HydratorInterface {

    /**
     * @param FicheMetier $object
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
     * @param FicheMetier $object
     * @return FicheMetier
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        return $object;
    }

}