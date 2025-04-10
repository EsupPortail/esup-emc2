<?php

namespace EntretienProfessionnel\Form\EntretienProfessionnel;

use Application\Service\Agent\AgentServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use Laminas\Hydrator\HydratorInterface;

class EntretienProfessionnelHydrator implements HydratorInterface {
    use AgentServiceAwareTrait;
    use UserServiceAwareTrait;
    use CampagneServiceAwareTrait;

    /**
     * @param EntretienProfessionnel $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
//            'responsable' => ($object->getResponsable())?['id' => $object->getResponsable()->getId(), 'label' => $object->getResponsable()->getDenomination()]:null,
            'responsable' => ($object->getResponsable())?$object->getResponsable()->getId():null,
            'agent' => ($object->getAgent())?['id' => $object->getAgent()->getId(), 'label' => $object->getAgent()->getDenomination()]:null,
            'date_entretien'  => ($object->getDateEntretien())?$object->getDateEntretien()->format('Y-m-d'):null,
            'heure_entretien' => ($object->getDateEntretien())?$object->getDateEntretien()->format('H:i'):null,
            'campagne' => ($object->getCampagne())?$object->getCampagne()->getId():null,
            'lieu_entretien' => $object->getLieu(),
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
//        $reponsable = $this->getAgentService()->getAgent($data['responsable']['id']);
        $reponsable = $this->getAgentService()->getAgent($data['responsable']);
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
