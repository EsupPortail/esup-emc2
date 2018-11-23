<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\AgentStatus;
use Zend\Stdlib\Hydrator\HydratorInterface;

class AgentStatusHydrator implements HydratorInterface {

    /**
     * @param AgentStatus $object
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
     * @param AgentStatus $object
     * @return AgentStatus
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        return $object;
    }

}