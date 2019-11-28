<?php

namespace Application\Form\ConfigurationFicheMetier;

use Application\Entity\Db\ConfigurationFicheMetier;
use Zend\Hydrator\HydratorInterface;

class ConfigurationFicheMetierHydrator implements HydratorInterface {

    /**
     * @param ConfigurationFicheMetier $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'operation' => $object->getOperation(),
            'type' => $object->getEntityType(),
            'select' => $object->getEntityId(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param ConfigurationFicheMetier $object
     * @return ConfigurationFicheMetier
     */
    public function hydrate(array $data, $object)
    {
        $object->setOperation($data['operation']);
        $object->setEntityType($data['type']);
        $object->setEntityId($data['select']);
        return $object;
    }


}