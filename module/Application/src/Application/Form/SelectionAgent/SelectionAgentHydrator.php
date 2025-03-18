<?php

namespace Application\Form\SelectionAgent;

use Application\Entity\HasAgentInterface;
use Application\Service\Agent\AgentServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class SelectionAgentHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var HasAgentInterface $object */
        $data = [
            'agent-sas'     => ($object->getAgent())?['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()]:null,
        ];
        return $data;
    }

    public function hydrate(array $data,object $object): object
    {
        $agent = $this->getAgentService()->getAgent($data['agent-sas']['id'], true);

        /** @var HasAgentInterface $object */
        $object->setAgent($agent);
        return $object;
    }

}