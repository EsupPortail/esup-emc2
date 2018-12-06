<?php

namespace Application\Form\Agent;

use Application\Entity\Db\MissionComplementaire;
use Application\Service\Agent\AgentServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class MissionComplementaireHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;

    /**
     * @param MissionComplementaire $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
//            'agent' => $object->getAgent(),
            'libelle' => $object->getLibelle(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param MissionComplementaire $object
     * @return MissionComplementaire
     */
    public function hydrate(array $data, $object)
    {
//        $agent = $this->getAgentService()->getAgent($data['agent']);

        $object->setLibelle($data['libelle']);
//        $object->setAgent($agent);
        return $object;
    }

}