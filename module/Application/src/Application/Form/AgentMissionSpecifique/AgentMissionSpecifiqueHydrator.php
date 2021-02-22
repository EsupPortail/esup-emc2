<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Entity\Db\AgentMissionSpecifique;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use DateTime;
use Zend\Hydrator\HydratorInterface;

class AgentMissionSpecifiqueHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use MissionSpecifiqueServiceAwareTrait;
    use StructureServiceAwareTrait;

    /**
     * @param AgentMissionSpecifique $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'mission'   => ($object->getMission()?$object->getMission()->getId():null),
            'agent'     => ($object->getAgent())?['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()]:null,
            'structure' => ($object->getStructure())?['id' => $object->getStructure()->getId(), 'label' => $object->getStructure()->getLibelleLong()]:null,
            'debut'     => ($object->getDateDebut()?$object->getDateDebut()->format('Y-m-d'):null),
            'fin'       => ($object->getDateFin()?$object->getDateFin()->format('Y-m-d'):null),
            'decharge'  => ($object->getDecharge()?$object->getDecharge():null)
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param AgentMissionSpecifique $object
     * @return AgentMissionSpecifique
     */
    public function hydrate(array $data, $object)
    {
        $mission = $this->getMissionSpecifiqueService()->getMissionSpecifique($data['mission']);
        $agent = $this->getAgentService()->getAgent($data['agent']['id']);
        $structure = $this->getStructureService()->getStructure($data['structure']['id']);
        $debut = ($data['debut']?DateTime::createFromFormat('Y-m-d', $data['debut']):null);
        $fin   = ($data['fin']?DateTime::createFromFormat('Y-m-d', $data['fin']):null);
        $decharge   = (isset($data['decharge']) AND trim($data['decharge']) !== '')?$data['decharge']:null;

        $object->setAgent($agent);
        $object->setMission($mission);
        $object->setStructure($structure);
        $object->setDateDebut($debut);
        $object->setDateFin($fin);
        $object->setDecharge($decharge);
        return $object;
    }

}