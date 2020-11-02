<?php

namespace Application\Form\CompetenceType;

use Application\Entity\Db\CompetenceType;
use Zend\Hydrator\HydratorInterface;

class CompetenceTypeHydrator implements HydratorInterface {

    /**
     * @var CompetenceType $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'ordre' => $object->getOrdre(),
        ];

        return $data;
    }

    /**
     * @param array $data
     * @param CompetenceType $object
     * @return CompetenceType
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setOrdre($data['ordre']);
        return $object;
    }


}