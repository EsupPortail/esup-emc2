<?php

namespace Application\Form\EntretienProfessionnel;

use Application\Entity\Db\EntretienProfessionnel;
use Application\Service\Agent\AgentServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use DateTime;
use Zend\Hydrator\HydratorInterface;

class EntretienProfessionnelHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use UserServiceAwareTrait;

    /**
     * @param EntretienProfessionnel $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'responsable' => $object->getResponsable(),
            'agent' => $object->getAgent(),
            'annee' => $object->getAnnee(),
            'date_entretien' => $object->getDateEntretien(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param EntretienProfessionnel $object
     * @return EntretienProfessionnel
     */
    public function hydrate(array $data, $object)
    {
        $reponsable = $this->getUserService()->getUtilisateur($data['responsable']);
        $agent      = $this->getAgentService()->getAgent($data['agent']);
        $date       = DateTime::createFromFormat('d/m/Y', $data['date_entretien']);

        $object->setResponsable($reponsable);
        $object->setAgent($agent);
        $object->setAnnee($data['annee']);
        $object->setDateEntretien($date);

        return $object;
    }

}