<?php

namespace UnicaenUtilisateur\Form\User;

trait UserFormAwareTrait {
    
    /** @var UserForm */
    private $userForm;

    /**
     * @return UserForm
     */
    public function getUserForm()
    {
        return $this->userForm;
    }

    /**
     * @param UserForm $userForm
     * @return UserForm
     */
    public function setUserForm($userForm)
    {
        $this->userForm = $userForm;
        return $this->userForm;
    }
}