<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FichePoste;
use Zend\Stdlib\Hydrator\HydratorInterface;

class FicheMetierCreationHydrator implements HydratorInterface {

    /**
     * @param FichePoste $object
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
     * @param FichePoste $object
     * @return FichePoste
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        return $object;
    }

}