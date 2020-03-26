<?php

namespace UnicaenPrivilege\Form\Privilege;

trait  PrivilegeFormAwareTrait {

    /** @var PrivilegeForm $privilegeForm */
    private $privilegeForm;

    /**
     * @return PrivilegeForm
     */
    public function getPrivilegeForm()
    {
        return $this->privilegeForm;
    }

    /**
     * @param PrivilegeForm $privilegeForm
     * @return PrivilegeForm
     */
    public function setPrivilegeForm($privilegeForm)
    {
        $this->privilegeForm = $privilegeForm;
        return $this->privilegeForm;
    }


}