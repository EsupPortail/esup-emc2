<?php

namespace FichePoste\Form\Expertise;

use FichePoste\Entity\Db\Expertise;
use Laminas\Hydrator\HydratorInterface;

class ExpertiseHydrator implements HydratorInterface {

    /**
     * @param Expertise $object
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
     * @param Expertise $object
     * @return Expertise
     */
    public function hydrate(array $data, $object): object
    {
        $object->setLibelle($data['libelle']);
        $object->setDescription($data['description']);
        return $object;
    }

}
