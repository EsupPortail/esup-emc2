<?php

namespace Application\Form\FonctionDestination;

use Application\Entity\Db\FonctionDestination;
use Laminas\Hydrator\HydratorInterface;

class FonctionDestinationHydrator implements HydratorInterface {

    /**
     * @param FonctionDestination $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
        ];

        return $data;
    }

    /**
     * @param array $data
     * @param FonctionDestination $object
     * @return FonctionDestination
     */
    public function hydrate(array $data, $object)
    {
        $code = (isset($data['code']) AND trim($data['code']) !== "")?trim($data['code']):null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== "")?trim($data['libelle']):null;

        $object->setCode($code);
        $object->setLibelle($libelle);
        return $object;
    }

}