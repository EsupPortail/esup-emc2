<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Entity\Db\AgentMissionSpecifique;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\RessourceRh\RessourceRhServiceAwareTrait;
use Application\Service\Structure\StructureServiceAwareTrait;
use DateTime;
use Zend\Hydrator\HydratorInterface;

class AgentMissionSpecifiqueHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use RessourceRhServiceAwareTrait;
    use StructureServiceAwareTrait;

    /**
     * @param AgentMissionSpecifique $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
//            'agent'     => ($object->getAgent()?$object->getAgent()->getId():null),
            'mission'   => ($object->getMission()?$object->getMission()->getId():null),
            'structure' => ($object->getStructure())?['id' => $object->getStructure()->getId(), 'label' => $object->getStructure()->getLibelleLong()]:null,
            'debut'     => ($object->getDateDebut()?$object->getDateDebut()->format('d/m/Y'):null),
            'fin'       => ($object->getDateFin()?$object->getDateFin()->format('d/m/Y'):null),
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
//        $agent = $this->getAgentService()->getAgent($data['agent']);
        $mission = $this->getRessourceRhService()->getMissionSpecifique($data['mission']);
        $structure = $this->getStructureService()->getStructure($data['structure']['id']);
        $debut = ($data['debut']?DateTime::createFromFormat('d/m/Y', $data['debut']):null);
        $fin   = ($data['fin']?DateTime::createFromFormat('d/m/Y', $data['fin']):null);
        $decharge   = isset($data['decharge'])?$data['decharge']:null;

//        $object->setAgent($agent);
        $object->setMission($mission);
        $object->setStructure($structure);
        $object->setDateDebut($debut);
        $object->setDateFin($fin);
        $object->setDecharge($decharge);
        return $object;
    }

}