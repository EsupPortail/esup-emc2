<?php

namespace UnicaenParametre\Form\Categorie;

use UnicaenParametre\Entity\Db\Categorie;
use Zend\Hydrator\HydratorInterface;

class CategorieHydrator implements HydratorInterface {

    /**
     * @param Categorie $object
     * @return array|void
     */
    public function extract($object)
    {
        $data = [
            'code' => $object->getCode(),
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
            'ordre' => $object->getOrdre(),
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
        $code = (isset($data['code']) and trim($data['code']) !== '')?trim($data['code']):null;
        $libelle = (isset($data['libelle']) and trim($data['libelle']) !== '')?trim($data['libelle']):null;
        $description = (isset($data['description']) and trim($data['description']) !== '')?trim($data['description']):null;
        $ordre = (isset($data['ordre']) and trim($data['ordre']) !== '')?trim($data['ordre']):null;

        $object->setCode($code);
        $object->setLibelle($libelle);
        $object->setDescription($description);
        $object->setOrdre($ordre);

        return $object;
    }

}