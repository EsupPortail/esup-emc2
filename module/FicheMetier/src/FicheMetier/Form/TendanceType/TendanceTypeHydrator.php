<?php

namespace FicheMetier\Form\TendanceType;

use FicheMetier\Entity\Db\TendanceType;
use Laminas\Hydrator\HydratorInterface;

class TendanceTypeHydrator implements HydratorInterface
{

    public function extract(object $object): array
    {
        /** @var TendanceType $object */
        $data = [
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'obligatoire' => $object->isObligatoire(),
            'ordre' => $object->getOrdre(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $code = (isset($data['code']) && trim($data['code']) !== null)?trim($data['code']):null;
        $libelle = (isset($data['libelle']) && trim($data['libelle']) !== "")?trim($data['libelle']):null;
        $description = (isset($data['description']) && trim($data['description']) !== "")?trim($data['description']):null;
        $obligatoire = (isset($data['obligatoire']) && $data['obligatoire'] === "1");
        $ordre = (isset($data['ordre']))?((int) $data['ordre']):null;

        /** @var TendanceType $object */
        $object->setCode($code);
        $object->setLibelle($libelle);
        $object->setDescription($description);
        $object->setObligatoire($obligatoire);
        $object->setOrdre($ordre);
        return $object;

    }

}