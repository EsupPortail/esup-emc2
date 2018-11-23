<?php

namespace Application\Service\Role;

use RuntimeException;

/**
 * @author jean-Philippe Metivier <jean-philippe.metivier at unicaen.fr>
 */
trait RoleServiceAwareTrait
{
    /**
     * @var RoleService
     */
    private $serviceRole;

    /**
     * @param RoleService $serviceRole
     * @return self
     */
    public function setRoleService( RoleService $serviceRole )
    {
        $this->serviceRole = $serviceRole;
        return $this;
    }

    /**
     * @return RoleService
     * @throws RuntimeException
     */
    public function getRoleService()
    {
        if (empty($this->serviceRole)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
            $this->serviceRole = $serviceLocator->get('RoleService');
        }
        return $this->serviceRole;
    }
}