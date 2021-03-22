<?php

namespace UnicaenPrivilege\Form\Privilege;

use UnicaenPrivilege\Entity\Db\Privilege;
use Zend\Hydrator\HydratorInterface;

class PrivilegeHydrator implements HydratorInterface {

    /**
     * @param Privilege $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle'     => $object->getLibelle(),
            'code'        => $object->getCode(),
            'ordre'       => $object->getOrdre() ?? null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Privilege $object
     * @return Privilege
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setCode($data['code']);
        if (isset($data['ordre']) AND $data['ordre'] !== '')
            $object->setOrdre((int) $data['ordre']);
        else
            $object->setOrdre(9999);
    }

}