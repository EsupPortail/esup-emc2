<?php

namespace Autoform\Form\Categorie;

use Autoform\Entity\Db\Categorie;
use Zend\Hydrator\HydratorInterface;

class CategorieHydrator implements HydratorInterface {

    /**
     * @param Categorie $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
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
        $object->setLibelle($data['libelle']);
        return $object;
    }


}