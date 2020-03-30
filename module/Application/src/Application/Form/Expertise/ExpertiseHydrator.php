<?php

namespace Application\Form\Expertise;

use Application\Entity\Db\Expertise;
use Zend\Hydrator\HydratorInterface;

class ExpertiseHydrator implements HydratorInterface {

    /**
     * @param Expertise $object
     * @return array
     */
    public function extract($object)
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
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setDescription($data['description']);
        return $object;
    }

}
