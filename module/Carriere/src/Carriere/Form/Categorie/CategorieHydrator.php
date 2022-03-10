<?php

namespace Carriere\Form\Categorie;

use Carriere\Entity\Db\Categorie;
use Zend\Hydrator\HydratorInterface;

class CategorieHydrator implements HydratorInterface {

    /**
     * @var Categorie $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Categorie $object
     * @return Categorie
     */
    public function hydrate(array $data, $object)
    {
        $code = (isset($data['code']) AND trim($data['code']) !== '')?trim($data['code']):null;
        $libelle = (isset($data['libelle']) AND trim($data['libelle']) !== '')?trim($data['libelle']):null;

        $object->setCode($code);
        $object->setLibelle($libelle);

        return $object;
    }


}