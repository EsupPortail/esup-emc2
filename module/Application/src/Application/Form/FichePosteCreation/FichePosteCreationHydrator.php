<?php

namespace Application\Form\FichePosteCreation;

use Application\Entity\Db\FichePoste;
use Zend\Stdlib\Hydrator\HydratorInterface;

class FichePosteCreationHydrator implements HydratorInterface {

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