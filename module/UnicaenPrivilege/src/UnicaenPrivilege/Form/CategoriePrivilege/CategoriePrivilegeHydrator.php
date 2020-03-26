<?php

namespace UnicaenPrivilege\Form\CategoriePrivilege;

use UnicaenPrivilege\Entity\Db\CategoriePrivilege;
use Zend\Hydrator\HydratorInterface;

class CategoriePrivilegeHydrator implements HydratorInterface {

    /**
     * @param CategoriePrivilege $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle'     => $object->getLibelle(),
            'code'        => $object->getCode(),
            'ordre'       => $object->getOrdre() ?? null,
            'namespace'   => $object->getNamespace() ?? null,
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param CategoriePrivilege $object
     * @return CategoriePrivilege
     */
    public function hydrate(array $data, $object)
    {
        $object->setLibelle($data['libelle']);
        $object->setCode($data['code']);
        if (isset($data['ordre']) AND $data['ordre'] !== '')
            $object->setOrdre((int) $data['ordre']);
        else
            $object->setOrdre(9999);
        $object->setNamespace($data['namespace']);
    }

}