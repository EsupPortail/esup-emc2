<?php

namespace Carriere\Form\NiveauFonction;

use Carriere\Entity\Db\NiveauFonction;
use Laminas\Hydrator\HydratorInterface;

class NiveauFonctionHydrator implements HydratorInterface
{
    public function extract(object $object): array
    {
        /** @var NiveauFonction $object */
        $data = [
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $code = (isset($data['code']) AND trim($data['code']) !== '') ? trim($data['code']) : null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== '') ? trim($data['libelle']) : null;
        $description = (isset($data['description']) AND trim($data['description']) !== '') ? trim($data['description']) : null;

        /** @var NiveauFonction $object */
        $object->setCode($code);
        $object->setLibelle($libelle);
        $object->setDescription($description);
        return $object;
    }


}
