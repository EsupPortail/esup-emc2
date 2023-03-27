<?php

namespace Element\Form\CompetenceType;

use Element\Entity\Db\CompetenceType;
use Laminas\Hydrator\HydratorInterface;

class CompetenceTypeHydrator implements HydratorInterface {

    /**
     * @var CompetenceType $object
     * @return array
     */
    public function extract($object): array
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
    public function hydrate(array $data, $object) : object
    {
        $object->setLibelle($data['libelle']);
        $object->setOrdre($data['ordre']);
        return $object;
    }


}