<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\MetierFamille;
use Zend\Stdlib\Hydrator\HydratorInterface;

class MetierFamilleHydrator implements HydratorInterface {

    /**
     * @param MetierFamille $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle'   => $object->getLibelle(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param MetierFamille $object
     * @return MetierFamille
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        return $object;
    }

}