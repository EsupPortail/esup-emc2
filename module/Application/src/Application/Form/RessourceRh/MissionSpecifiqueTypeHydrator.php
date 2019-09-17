<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\MissionSpecifiqueType;
use Zend\Hydrator\HydratorInterface;

class MissionSpecifiqueTypeHydrator implements HydratorInterface
{
    /**
     * @param MissionSpecifiqueType $object
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
     * @param MissionSpecifiqueType $object
     * @return MissionSpecifiqueType
     */
    public function hydrate(array $data, $object)
    {
        if (isset($data['libelle'])) {
            $object->setLibelle($data['libelle']);
        } else {
            $object->setLibelle(null);
        }
        return $object;
    }

}