<?php

namespace Utilisateur\Service\Privilege;

trait PrivilegeServiceAwareTrait {

    /** @var PrivilegeService $privilegeService */
    private $privilegeService;

    /**
     * @return PrivilegeService
     */
    public function getPrivilegeService()
    {
        return $this->privilegeService;
    }

    /**
     * @param PrivilegeService $privilegeService
     * @return PrivilegeService
     */
    public function setPrivilegeService($privilegeService)
    {
        $this->privilegeService = $privilegeService;
        return $this->privilegeService;
    }
}