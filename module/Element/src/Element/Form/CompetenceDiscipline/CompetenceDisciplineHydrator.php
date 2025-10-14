<?php

namespace Element\Form\CompetenceDiscipline;

use Element\Entity\Db\CompetenceDiscipline;
use Laminas\Hydrator\HydratorInterface;

class CompetenceDisciplineHydrator implements HydratorInterface {

    /**
     * @var CompetenceDiscipline $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
        ];

        return $data;
    }

    /**
     * @param array $data
     * @param CompetenceDiscipline $object
     * @return CompetenceDiscipline
     */
    public function hydrate(array $data, $object) : object
    {
        $object->setLibelle($data['libelle']);
        $object->setDescription($data['description']);
        return $object;
    }

}