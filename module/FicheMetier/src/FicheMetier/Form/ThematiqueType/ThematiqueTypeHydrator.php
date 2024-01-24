<?php

namespace FicheMetier\Form\ThematiqueType;

use FicheMetier\Entity\Db\ThematiqueType;
use Laminas\Hydrator\HydratorInterface;

class ThematiqueTypeHydrator implements HydratorInterface
{

    public function extract(object $object): array
    {
        /** @var ThematiqueType $object */
        $data = [
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'obligatoire' => $object->isObligatoire(),
        ];
        return $data;
    }

    public function hydrate(array $data, object $object): object
    {
        $code = (isset($data['code']) && trim($data['code']) !== null)?trim($data['code']):null;
        $libelle = (isset($data['libelle']) && trim($data['libelle']) !== null)?trim($data['libelle']):null;
        $description = (isset($data['description']) && trim($data['description']) !== null)?trim($data['description']):null;
        $obligatoire = (isset($data['obligatoire']) && $data['obligatoire'] === "1");

        /** @var ThematiqueType $object */
        $object->setCode($code);
        $object->setLibelle($libelle);
        $object->setDescription($description);
        $object->setObligatoire($obligatoire);
        return $object;

    }

}