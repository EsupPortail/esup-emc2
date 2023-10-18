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
            'agent'     => ($object->getAgent())?['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()]:null,
            'campagne'  => ($object->getCampagne())?$object->getCampagne()->getId():null,
            'raison'    => $object->getRaison(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $agent      = (isset($data['agent']['id']))?$this->getAgentService()->getAgent($data['agent']['id']):null;
        $campagne   = (isset($data['campagne']))?$this->getCampagneService()->getCampagne($data['campagne']):null;
        $raison     = (isset($data['raison']) && trim($data['raison']) !== '')?trim($data['raison']):null;

        /** @var AgentForceSansObligation $object */
        $object->setAgent($agent);
        $object->setCampagne($campagne);
        $object->setRaison($raison);
        return $object;
    }

}