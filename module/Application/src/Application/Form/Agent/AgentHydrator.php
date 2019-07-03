<?php

namespace Application\Form\Agent;

use Application\Entity\Db\Agent;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class AgentHydrator implements HydratorInterface {
    use RessourceRhServiceAwareTrait;

    /**
     * @param Agent $object
     * @return array
     */
    public function extract($object)
    {
        $missionId = [];
        foreach ($object->getMissions() as $mission) {
            $missionId[] = $mission->getId();
        }

        $data = [
            'quotite' => $object->getQuotite(),
            'missions' => $missionId,
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

        foreach ($object->getMissions() as $mission) $object->removeMission($mission);

        foreach ($data['missions'] as $missionId) {
            $mission = $this->getRessourceRhService()->getMissionSpecifique($missionId);
            $object->addMission($mission);
        }

        return $object;
    }

}