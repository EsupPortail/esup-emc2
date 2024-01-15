<?php

namespace Observation\Form\ObservationType;

use Entity\Db\ObservationType;
use Laminas\Hydrator\HydratorInterface;

class ObservationTypeHydrator implements HydratorInterface {

    public function extract(object $object): array
    {
        /** @var ObservationType $object */
        $data = [
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
            'categorie' => $object->getCategorie(),
        ];

        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $code = (isset($data['code']) && trim ($data['code']) !== '')?trim($data['code']):null;
        $libelle = (isset($data['libelle']) && trim ($data['libelle']) !== '')?trim($data['libelle']):null;
        $categorie = (isset($data['categorie']) && trim ($data['categorie']) !== '')?trim($data['categorie']):null;

        /** @var ObservationType $object */
        $object->setCode($code);
        $object->setLibelle($libelle);
        $object->setCategorie($categorie);
        return $object;
    }


}