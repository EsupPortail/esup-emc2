<?php

namespace Formation\Form\Inscription;

use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Entity\Db\Inscription;
use Formation\Service\Session\SessionServiceAwareTrait;
use Formation\Service\StagiaireExterne\StagiaireExterneServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class InscriptionHydrator implements HydratorInterface
{
    use AgentServiceAwareTrait;
    use SessionServiceAwareTrait;
    use StagiaireExterneServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var Inscription $object */
        $data = [
            'session' => ($object->getSession()) ? ['id' => $object->getSession()->getId(), 'label' => $object->getSession()->getFormation()->getLibelle()] : null,
            'type' => ($object->getStagiaire() !== null) ? 'stagiaire' : 'agent',
            'agent' => ($object->getAgent()) ? ['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()] : null,
            'stagiaire' => ($object->getStagiaire()) ? ['id' => $object->getStagiaire()->getId(), 'label' => $object->getStagiaire()->getDenomination()] : null,
        ];
        return $data;
    }

    /** @return Inscription */
    public function hydrate(array $data, object $object): object
    {
        /** @var Inscription $object */
        $session = (isset($data['session']) && $data['session']['id'] !== '') ? $this->getSessionService()->getSession((int)$data['session']['id']) : null;
        $type = $data['type'] ?? null;
        $agent = (isset($data['agent']) && $data['agent']['id'] !== '') ? $this->getAgentService()->getAgent($data['agent']['id'], true, false) : null;
        $stagiaire = (isset($data['stagiaire']) && $data['stagiaire']['id'] !== '') ? $this->getStagiaireExterneService()->getStagiaireExterne($data['stagiaire']['id']) : null;

        $object->setSession($session);
        switch ($type) {
            case 'agent' :
                $object->setAgent($agent);
                $object->setStagiaire(null);
                break;
            case 'stagiaire' :
                $object->setAgent(null);
                $object->setStagiaire($stagiaire);
                break;
            default :
                $object->setAgent(null);
                $object->setStagiaire(null);
                break;
        }
        return $object;
    }

}