<?php

namespace Application\Form\EntretienProfessionnelObservation;

use Application\Entity\Db\EntretienProfessionnelObservation;
use Zend\Hydrator\HydratorInterface;

class EntretienProfessionnelObservationHydrator implements HydratorInterface {

    /**
     * @param EntretienProfessionnelObservation $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'obs-entretien' => $object->getObservationAgentEntretien(),
            'obs-perspective' => $object->getObservationAgentPerspective(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param EntretienProfessionnelObservation $object
     * @return EntretienProfessionnelObservation
     */
    public function hydrate(array $data, $object)
    {
        $entretien = (isset($data['obs-entretien']) AND trim($data['obs-entretien']) !== '')?trim($data['obs-entretien']):null;
        $perspective = (isset($data['obs-perspective']) AND trim($data['obs-perspective']) !== '')?trim($data['obs-perspective']):null;

        $object->setObservationAgentEntretien($entretien);
        $object->setObservationAgentPerspective($perspective);

        return $object;
    }


}