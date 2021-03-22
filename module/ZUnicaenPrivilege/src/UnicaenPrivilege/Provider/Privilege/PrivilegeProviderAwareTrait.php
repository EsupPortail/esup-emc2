<?php

namespace UnicaenPrivilege\Provider\Privilege;

trait PrivilegeProviderAwareTrait
{
    /**
     * description
     *
     * @var PrivilegeProviderInterface
     */
    private $privilegeProvider;

    /**
     *
     * @param PrivilegeProviderInterface $privilegeProvider
     * @return self
     */
    public function setPrivilegeProvider( PrivilegeProviderInterface $privilegeProvider )
    {
        $this->privilegeProvider = $privilegeProvider;
        return $this;
    }

    /**
     *
     * @return PrivilegeProviderInterface
     */
    public function getPrivilegeProvider()
    {
        return $this->privilegeProvider;
    }

}