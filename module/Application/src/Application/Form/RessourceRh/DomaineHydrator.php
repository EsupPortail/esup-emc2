<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\Domaine;
use Zend\Stdlib\Hydrator\HydratorInterface;

class DomaineHydrator implements HydratorInterface {

    /**
     * @param Domaine $object
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
     * @param Domaine $object
     * @return Domaine
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        return $object;
    }

}