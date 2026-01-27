<?php

namespace FicheMetier\Form\CodeEmploiType;

use FicheMetier\Entity\Db\FicheMetier;
use Laminas\Hydrator\HydratorInterface;

class CodeEmploiTypeHydrator implements HydratorInterface {

    /**
     * @param FicheMetier $object
     * @return array
     */
    public function extract($object): array
    {
        $data = [
            "code-emploi-type" => $object->getCodesEmploiType(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FicheMetier $object
     * @return FicheMetier
     */
    public function hydrate(array $data, $object) : object
    {
        $codes = (isset($data['code-emploi-type']) AND trim($data['code-emploi-type']) != '')?trim($data['code-emploi-type']):null;
        $object->setCodesEmploiType($codes);

        return $object;
    }

}