<?php

namespace Autoform\Form\Formulaire;

use Autoform\Entity\Db\Formulaire;
use Zend\Stdlib\Hydrator\HydratorInterface;

class FormulaireHydrator implements HydratorInterface {

    /**
     * @param Formulaire $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'description' => $object->getDescription(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Formulaire $object
     * @return Formulaire
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setDescription($data['description']?:null);
        return $object;
    }


}