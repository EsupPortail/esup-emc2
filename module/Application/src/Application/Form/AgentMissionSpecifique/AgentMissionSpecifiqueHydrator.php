<?php

namespace Application\Form\AgentMissionSpecifique;

use Application\Entity\Db\AgentMissionSpecifique;
use Application\Form\HasPeriode\HasPeriodeFieldset;
use Application\Service\Agent\AgentServiceAwareTrait;
use MissionSpecifique\Service\MissionSpecifique\MissionSpecifiqueServiceAwareTrait;
use DateTime;
use Laminas\Hydrator\HydratorInterface;
use Structure\Service\Structure\StructureServiceAwareTrait;

class AgentMissionSpecifiqueHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use MissionSpecifiqueServiceAwareTrait;
    use StructureServiceAwareTrait;

    /**
     * @param AgentMissionSpecifique $object
     * @return array
     */
    public function extract(object $object) : array
    {
        $data = [
            'mission'   => ($object->getMission()?$object->getMission()->getId():null),
            'agent'     => ($object->getAgent())?['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()]:null,
            'structure' => ($object->getStructure())?['id' => $object->getStructure()->getId(), 'label' => $object->getStructure()->getLibelleLong()]:null,
            'HasPeriode'        => [
                'date_debut' => ($object->getDateDebut())?$object->getDateDebut()->format(HasPeriodeFieldset::format):null,
                'date_fin'   => ($object->getDateFin())?$object->getDateFin()->format(HasPeriodeFieldset::format):null,
            ],
            'decharge'  => ($object->getDecharge()?$object->getDecharge():null)
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param AgentMissionSpecifique $object
     * @return AgentMissionSpecifique
     */
    public function hydrate(array $data, object $object)
    {
        $mission = $this->getMissionSpecifiqueService()->getMissionSpecifique($data['mission']);
        $agent = $this->getAgentService()->getAgent($data['agent']['id']);
        $structure = $this->getStructureService()->getStructure((int) $data['structure']['id']);
        $dataDebut = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_debut']) AND trim($data['HasPeriode']['date_debut']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_debut']):null;
        $dateFin = (isset($data['HasPeriode']) AND isset($data['HasPeriode']['date_fin']) AND trim($data['HasPeriode']['date_fin']) !== '')?DateTime::createFromFormat(HasPeriodeFieldset::format, $data['HasPeriode']['date_fin']):null;
        $decharge   = (isset($data['decharge']) AND trim($data['decharge']) !== '')?$data['decharge']:null;

        $object->setAgent($agent);
        $object->setMission($mission);
        $object->setStructure($structure);
        $object->setDateDebut($dataDebut);
        $object->setDateFin($dateFin);
        $object->setDecharge($decharge);
        return $object;
    }

}