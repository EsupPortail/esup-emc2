<?php

namespace UnicaenContact\Form\Type;


use Laminas\Hydrator\HydratorInterface;
use UnicaenContact\Entity\Db\Type;

class TypeHydrator implements HydratorInterface
{

    public function extract(object $object): array
    {
        /** @var Type $object */
        $data = [
            'libelle' => $object->getLibelle(),
            'code' => $object->getCode(),   
            'description' => $object->getDescription(),
        ];
        return $data;        
    }

    public function hydrate(array $data, object $object): object
    {
        /** @var Type $object */
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== null) ? trim($data['libelle']) : null;
        $code = (isset($data['code']) AND trim($data['code']) !== null) ? trim($data['code']) : null;
        $description = (isset($data['description']) AND trim($data['description']) !== "") ? trim($data['description']) : null;

        $object->setLibelle($libelle);
        $object->setCode($code);
        $object->setDescription($description);
        return $object;
    }
}