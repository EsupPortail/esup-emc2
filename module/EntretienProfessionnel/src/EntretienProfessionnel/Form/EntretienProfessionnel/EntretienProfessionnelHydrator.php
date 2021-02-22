<?php

namespace EntretienProfessionnel\Form\EntretienProfessionnel;

use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class EntretienProfessionnelHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use UserServiceAwareTrait;
    use CampagneServiceAwareTrait;

    /**
     * @param EntretienProfessionnel $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'responsable' => $object->getResponsable(),
            'agent' => ($object->getAgent())?['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()]:null,
            'date_entretien'  => ($object->getDateEntretien())?$object->getDateEntretien()->format('Y-m-d'):null,
            'heure_entretien' => ($object->getDateEntretien())?$object->getDateEntretien()->format('H:i'):null,
            'campagne' => ($object->getCampagne())?$object->getCampagne()->getId():null,
            'lieu_entretien' => ($object->getLieu())?$object->getLieu():null,
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
        $date_day   = (isset($data['date_entretien']) AND trim($data['date_entretien']) !== "")?trim($data['date_entretien']):null;
        $date_time  = (isset($data['heure_entretien']) AND trim($data['heure_entretien']) !== "")?trim($data['heure_entretien']):null;
        $date = ($date_day !== null AND $date_time !== null)?DateTime::createFromFormat("Y-m-d H:i", $date_day." ".$date_time):null;

        $campagne   = $this->getCampagneService()->getCampagne($data['campagne']);
        $lieu       = (isset($data['lieu_entretien']) AND trim($data['lieu_entretien']) !== "")?trim($data['lieu_entretien']):null;

        $object->setResponsable($reponsable);
        $object->setAgent($agent);
        $object->setDateEntretien($date);
        $object->setCampagne($campagne);
        $object->setLieu($lieu);

        return $object;
    }

}
