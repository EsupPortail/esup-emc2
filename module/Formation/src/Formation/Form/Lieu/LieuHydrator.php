<?php

namespace Formation\Form\Lieu;

use Formation\Entity\Db\Lieu;
use Laminas\Hydrator\HydratorInterface;

class LieuHydrator implements HydratorInterface {

    public function extract(object $object): array
    {
        /** @var Lieu $object */
        $data = [
            'libelle' => $object->getLibelle(),
            'batiment' => $object->getBatiment(),
            'campus' => $object->getCampus(),
            'ville' => $object->getVille(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $libelle = (isset($data['libelle']) && trim($data['libelle']) !== '') ? trim($data['libelle']) : null;
        $batiment = (isset($data['batiment']) && trim($data['batiment']) !== '') ? trim($data['batiment']) : null;
        $campus = (isset($data['campus']) && trim($data['campus']) !== '') ? trim($data['campus']) : null;
        $ville = (isset($data['ville']) && trim($data['ville']) !== '') ? trim($data['ville']) : null;

        $object->setLibelle($libelle);
        $object->setBatiment($batiment);
        $object->setCampus($campus);
        $object->setVille($ville);
        return $object;
    }
}