<?php

namespace Application\Form\EntretienProfessionnel;

use Application\Entity\Db\EntretienProfessionnel;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelCampagneServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use DateTime;
use Zend\Hydrator\HydratorInterface;

class EntretienProfessionnelHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use UserServiceAwareTrait;
    use EntretienProfessionnelCampagneServiceAwareTrait;

    /**
     * @param EntretienProfessionnel $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'responsable' => $object->getResponsable(),
            'agent' => ($object->getAgent())?['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()]:null,
            'date_entretien' => $object->getDateEntretien(),
            'campagne' => ($object->getCampagne())?$object->getCampagne()->getId():null,
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
        $reponsable = $this->getUserService()->getUtilisateur($data['responsable']['id']);
        $agent      = $this->getAgentService()->getAgent($data['agent']['id']);
        $date       = DateTime::createFromFormat('d/m/Y', $data['date_entretien']);
        $campagne   = $this->getEntretienProfessionnelCampagneService()->getEntretienProfessionnelCampagne($data['campagne']);

        $object->setResponsable($reponsable);
        $object->setAgent($agent);
        $object->setDateEntretien($date);
        $object->setCampagne($campagne);

        return $object;
    }

}
