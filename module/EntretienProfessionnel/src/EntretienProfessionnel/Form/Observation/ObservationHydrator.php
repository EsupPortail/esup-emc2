<?php

namespace EntretienProfessionnel\Form\Observation;

use EntretienProfessionnel\Entity\Db\Observation;
use Laminas\Hydrator\HydratorInterface;

class ObservationHydrator implements HydratorInterface {

    /**
     * @param Observation $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'obs-entretien' => $object->getObservationAgentEntretien(),
            'obs-perspective' => $object->getObservationAgentPerspective(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Observation $object
     * @return Observation
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