<?php

namespace EmploiRepere\Form\EmploiRepere;

use EmploiRepere\Entity\Db\EmploiRepere;
use Laminas\Hydrator\HydratorInterface;

class EmploiRepereHydrator implements hydratorInterface {

    public function extract(object $object): array
    {
        /** @var EmploiRepere $object */
        $data = [
            'libelle' => $object?->getLibelle(),
            'code' => $object?->getCode(),
            'description' => $object?->getDescription(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== '') ? trim($data['libelle']) : null;
        $code = (isset($data['code']) AND trim($data['code']) !== '') ? trim($data['code']) : null;
        $description = (isset($data['description']) AND trim($data['description']) !== '') ? trim($data['description']) : null;

        /** @var EmploiRepere $object */
        $object->setLibelle($libelle);
        $object->setCode($code);
        $object->setDescription($description);
        return $object;
    }

}
