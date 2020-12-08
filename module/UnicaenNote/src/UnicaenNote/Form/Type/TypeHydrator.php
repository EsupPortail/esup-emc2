<?php

namespace UnicaenNote\Form\Type;

use UnicaenNote\Entity\Db\Type;
use Zend\Hydrator\HydratorInterface;

class TypeHydrator implements HydratorInterface {

    /**
     * @param Type $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
            'style' => $object->getStyle(),
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Type $object
     * @return Type
     */
    public function hydrate(array $data, $object)
    {
        $code = (isset($data['code']) AND trim($data['code']) !== '')?trim($data['code']):null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null;
        $style = (isset($data['style']) AND trim($data['style']) !== '')?trim($data['style']):null;
        $description = (isset($data['description']) AND trim($data['description']) !== '')?trim($data['description']):null;

        $object->setCode($code);
        $object->setLibelle($libelle);
        $object->setStyle($style);
        $object->setDescription($description);
        return $object;
    }


}