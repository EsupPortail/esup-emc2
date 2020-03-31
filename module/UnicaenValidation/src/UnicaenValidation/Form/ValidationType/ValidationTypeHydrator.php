<?php

namespace UnicaenValidation\Form\ValidationType;

use UnicaenValidation\Entity\Db\ValidationType;
use Zend\Hydrator\HydratorInterface;

class ValidationTypeHydrator implements HydratorInterface {

    /**
     * @param ValidationType $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            "code" => $object->getCode(),
            "libelle" => $object->getLibelle(),
            "refusable" => $object->isRefusable(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param ValidationType $object
     * @return ValidationType
     */
    public function hydrate(array $data, $object)
    {
        $object->setCode(isset($data['code'])?$data['code']:null);
        $object->setLibelle(isset($data['libelle'])?$data['libelle']:null);
        $object->setRefusable(isset($data['refusable'])?$data['refusable']:null);
        return $object;
    }

}