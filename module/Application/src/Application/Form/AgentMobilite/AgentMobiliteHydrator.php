<?php

namespace Application\Form\AgentMobilite;

use Application\Entity\Db\AgentMobilite;
use Application\Service\Agent\AgentServiceAwareTrait;
use Carriere\Service\Mobilite\MobiliteServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class AgentMobiliteHydrator implements HydratorInterface
{
    use AgentServiceAwareTrait;
    use MobiliteServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var AgentMobilite $object */
        $data = [
            'agent' => ($object->getAgent()) ? ['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()] : null,
            'mobilite' => ($object->getMobilite()) ? $object->getMobilite()->getId() : null,
            'commentaire' => $object->getCommentaire(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $agent = (isset($data['agent']) and isset($data['agent']['id']) and trim($data['agent']['id']) !== '') ? $this->getAgentService()->getAgent($data['agent']['id']) : null;
        $mobilite = (isset($data['mobilite']) ) ? $this->getMobiliteService()->getMobilite($data['resultat']) : null;
        $commentaire = (isset($data['commentaire'])  and trim($data['commentaire']) !== '') ? trim($data['commentaire']) : null;

        /** @var AgentMobilite $object */
        $object->setAgent($agent);
        $object->setMobilite($mobilite);
        $object->setCommentaire($commentaire);

        return $object;
    }

}