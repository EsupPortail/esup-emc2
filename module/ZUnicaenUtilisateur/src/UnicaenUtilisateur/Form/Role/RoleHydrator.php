<?php

namespace UnicaenUtilisateur\Form\Role;

use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use Zend\Hydrator\HydratorInterface;

class RoleHydrator implements HydratorInterface {
    use RoleServiceAwareTrait;

    /**
     * @param Role $object
     * @return array
     */
    public function extract($object)
    {
        $data = [
            'libelle' => $object->getLibelle(),
            'role_id' => $object->getRoleId(),
            'parent_id' => ($object->getParent())?$object->getParent()->getId():null,
            'is_default' => $object->getIsDefault(),
            'ldap_filter' => $object->getLdapFilter(),
        ];
        return $data;
    }

    /**
     * @param array $data
     * @param Role $object
     * @return Role
     */
    public function hydrate(array $data, $object)
    {
        $parent = ($data['parent_id'] !== '')?$this->getRoleService()->getRole($data['parent_id']):null;

        if ($data['libelle'] !== '') {
            $object->setLibelle($data['libelle']);
        } else {
            $object->setLibelle(null);
        }
        if ($data['role_id'] !== '') {
            $object->setRoleId($data['role_id']);
        } else {
            $object->setRoleId(null);
        }
        $object->setParent($parent);
        $object->setIsDefault($data['is_default']);
        if ($data['ldap_filter'] !== '') $object->setLdapFilter($data['ldap_filter']); else $object->setLdapFilter(null);

        return $object;
    }


}