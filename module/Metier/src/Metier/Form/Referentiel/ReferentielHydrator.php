<?php

namespace Metier\Form\Referentiel;

use Metier\Entity\Db\Referentiel;
use Laminas\Hydrator\HydratorInterface;

class ReferentielHydrator implements HydratorInterface {

    /**
     * @param Referentiel $object
     * @return array
     */
    public function extract($object): array
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
     * @param Referentiel $object
     * @return Referentiel
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