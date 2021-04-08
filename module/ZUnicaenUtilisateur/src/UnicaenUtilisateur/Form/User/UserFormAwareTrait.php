<?php

namespace UnicaenUtilisateur\Form\User;

trait UserFormAwareTrait {
    
    /** @var UserForm */
    private $userForm;

    /**
     * @return UserForm
     */
    public function getUserForm() : UserForm
    {
        return $this->userForm;
    }

    /**
     * @param UserForm $userForm
     * @return UserForm
     */
    public function setUserForm(UserForm $userForm) : UserForm
    {
        $this->userForm = $userForm;
        return $this->userForm;
    }
}