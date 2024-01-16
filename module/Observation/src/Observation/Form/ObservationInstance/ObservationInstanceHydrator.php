<?php

namespace Observation\Form\ObservationInstance;

use Laminas\Hydrator\HydratorInterface;
use Observation\Entity\Db\ObservationInstance;
use Observation\Service\ObservationType\ObservationTypeServiceAwareTrait;

class ObservationInstanceHydrator implements HydratorInterface {
    use ObservationTypeServiceAwareTrait;

    public function extract(object $object): array
    {
        /** @var ObservationInstance $object */
        $data = [
            'observationtype' => ($object->getType())?$object->getType()->getId():null,
            'observation' => $object->getObservation(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $type = (isset($data['observationtype']))?$this->getObservationTypeService()->getObservationType($data['observationtype']):null;
        $observation = (isset($data['observation']) && trim($data['observation']) !== '')?trim($data['observation']):null;

        /** @var ObservationInstance $object */
        $object->setType($type);
        $object->setObservation($observation);
        return $object;
    }


}