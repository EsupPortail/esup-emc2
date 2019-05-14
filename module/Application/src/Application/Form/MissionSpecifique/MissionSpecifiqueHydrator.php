<?php

namespace Application\Form\MissionSpecifique;

use Application\Entity\Db\MissionSpecifique;
use Zend\Stdlib\Hydrator\HydratorInterface;

class MissionSpecifiqueHydrator implements HydratorInterface {

    /**
     * @param MissionSpecifique $object
     * @return array
     */
    public function extract($object)
    {
        $data = [];
        $data['libelle'] = $object->getLibelle();
        return $data;
    }

    /**
     * @param array $data
     * @param MissionSpecifique $object
     * @return MissionSpecifique
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        return $object;
    }
}