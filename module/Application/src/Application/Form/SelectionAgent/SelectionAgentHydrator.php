<?php

namespace Application\Form\SelectionAgent;

use Application\Entity\HasAgentInterface;
use Application\Service\Agent\AgentServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class SelectionAgentHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;

    /**
     * @param HasAgentInterface $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'agent'     => ($object->getAgent())?['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()]:null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param HasAgentInterface $object
     * @return HasAgentInterface
     */
    public function hydrate(array $data, $object)
    {
        $agent = $this->getAgentService()->getAgent($data['agent']['id']);
        $object->setAgent($agent);
        return $object;
    }

}