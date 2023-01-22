<?php

namespace Formation\Form\PlanDeFormation;

use Formation\Entity\Db\PlanDeFormation;
use Laminas\Hydrator\HydratorInterface;

class PlanDeFormationHydrator implements HydratorInterface {

    /**
     * @param PlanDeFormation $object
     * @return array
     */
    public function extract(object $object): array
    {
        $data = [
            'annee' => $object->getAnnee(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param PlanDeFormation $object
     * @return PlanDeFormation
     */
    public function hydrate(array $data, object $object) : object
    {
        $annee = (isset($data['annee']) AND trim($data['annee']) !== "")?trim($data['annee']):null;

        $object->setAnnee($annee);
        return $object;
    }


}