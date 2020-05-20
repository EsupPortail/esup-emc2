<?php

namespace Application\Form\MetierReferentiel;

use Application\Entity\Db\MetierReferentiel;
use Zend\Hydrator\HydratorInterface;

class MetierReferentielHydrator implements HydratorInterface {

    /**
     * @param MetierReferentiel $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle_court' => $object->getLibelleCourt(),
            'libelle_long'  => $object->getLibelleLong(),
            'prefix'        => $object->getPrefix(),
            'type'          => $object->getType(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param MetierReferentiel $object
     * @return MetierReferentiel
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelleCourt($data['libelle_court']);
        $object->setLibelleLong($data['libelle_long']);
        $object->setPrefix($data['prefix']);
        $object->setType($data['type']);
        return $object;
    }


}