<?php

namespace EntretienProfessionnel\Form\AgentForceSansObligation;

use Application\Service\Agent\AgentServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\AgentForceSansObligation;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;



class AgentForceSansObligationHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use CampagneServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var AgentForceSansObligation $object */
        $data = [
            'agentsearch'     => ($object->getAgent())?['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()]:null,
            'campagne'  => ($object->getCampagne())?$object->getCampagne()->getId():null,
            'type'      => $object->getType(),
            'raison'    => $object->getRaison(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $agent      = (isset($data['agentsearch']['id']))?$this->getAgentService()->getAgent($data['agentsearch']['id']):null;
        $campagne   = (isset($data['campagne']))?$this->getCampagneService()->getCampagne($data['campagne']):null;
        $type       = (isset($data['type']) && trim($data['type']) !== '')?trim($data['type']):null;
        $raison     = (isset($data['raison']) && trim($data['raison']) !== '')?trim($data['raison']):null;

        /** @var AgentForceSansObligation $object */
        $object->setAgent($agent);
        $object->setCampagne($campagne);
        $object->setType($type);
        $object->setRaison($raison);
        return $object;
    }

}