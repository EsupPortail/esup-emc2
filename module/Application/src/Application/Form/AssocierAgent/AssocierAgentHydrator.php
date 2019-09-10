<?php

namespace Application\Form\AssocierAgent;

use Application\Entity\Db\FichePoste;
use Application\Service\Agent\AgentServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class AssocierAgentHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;

    /**
     * @param FichePoste $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'agent' => ($object->getAgent())?$object->getAgent()->getId():0,
        ];
    }

    /**
     * @param FichePoste $object
     * @param array $data
     * @return FichePoste
     */
    public function hydrate(array $data, $object)
    {
        $agent = $this->getAgentService()->getAgent($data['agent']);

        if ($agent) {
            $object->setAgent($agent);
        } else {
            $object->setAgent(null);
        }
        return $object;
    }
}