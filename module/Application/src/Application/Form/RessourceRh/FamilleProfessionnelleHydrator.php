<?php

namespace Application\Form\RessourceRh;

use Application\Entity\Db\FamilleProfessionnelle;
use Zend\Stdlib\Hydrator\HydratorInterface;

class FamilleProfessionnelleHydrator implements HydratorInterface {

    /**
     * @param FamilleProfessionnelle $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle'   => $object->getLibelle(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param FamilleProfessionnelle $object
     * @return FamilleProfessionnelle
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        return $object;
    }

}