<?php

namespace Application\Form\Agent;

use Application\Entity\Db\Agent;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class AgentHydrator implements HydratorInterface {

    /**
     * @param Agent $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'quotite' => $object->getQuotite(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Agent $object
     * @return Agent
     */
    public function hydrate(array $data, $object)
    {
        $object->setQuotite($data['quotite']);


        foreach ($data['missions'] as $missionId) {
            $mission = $this->getRessourceRhService()->getMissionSpecifique($missionId);
        }

        return $object;
    }

}