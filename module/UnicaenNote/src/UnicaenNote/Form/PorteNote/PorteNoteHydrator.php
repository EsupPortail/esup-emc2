<?php

namespace UnicaenNote\Form\PorteNote;

use UnicaenNote\Entity\Db\PorteNote;
use Zend\Hydrator\HydratorInterface;

class PorteNoteHydrator implements HydratorInterface {

    /**
     * @param PorteNote $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle'     => $object->getAccroche(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param PorteNote $object
     * @return PorteNote
     */
    public function hydrate(array $data, $object)
    {
        $object->setAccroche($data['libelle']);
    }

}