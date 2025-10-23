<?php

namespace Carriere\Form\FonctionType;

use Carriere\Entity\Db\FonctionType;
use Laminas\Hydrator\HydratorInterface;

class FonctionTypeHydrator implements HydratorInterface
{
    public function extract(object $object): array
    {
        /** @var FonctionType $object */
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

        /** @var FonctionType $object */
        $object->setCode($code);
        $object->setLibelle($libelle);
        $object->setDescription($description);
        return $object;
    }


}
