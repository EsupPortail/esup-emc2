<?php

namespace Autoform\Form\Champ;

use Autoform\Entity\Db\Champ;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ChampHydrator implements HydratorInterface {

    /**
     * @param Champ $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'type'      => $object->getElement(),
            'libelle'   => $object->getLibelle(),
            'texte'     => $object->getTexte(),
            'options'   => $object->getOptions(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Champ $object
     * @return Champ
     */
    public function hydrate(array $data, $object)
    {
        $object->setElement($data['type']);
        $object->setLibelle($data['libelle']);
        $object->setTexte($data['texte']);
        $object->setOptions($data['options']);
        return $object;
    }


}