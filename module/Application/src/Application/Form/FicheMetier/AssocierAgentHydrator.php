<?php

namespace Application\Form\FicheMetier;

use Application\Entity\Db\FicheMetier;
use Application\Service\Agent\AgentServiceAwareTrait;
use Zend\Stdlib\Hydrator\HydratorInterface;

class AssocierAgentHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;

    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object)
    {
        return [
            'agent' => ($object->getAgent())?$object->getAgent()->getId():0,
        ];
    }

    /**
     * @param FicheMetier $object
     * @param array $data
     * @return FicheMetier
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