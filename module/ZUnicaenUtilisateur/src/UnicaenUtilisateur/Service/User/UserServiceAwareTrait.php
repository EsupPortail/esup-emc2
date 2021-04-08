<?php

namespace UnicaenUtilisateur\Service\User;

use RuntimeException;

trait UserServiceAwareTrait
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @param UserService $userService
     * @return UserService
     */
    public function setUserService( UserService $userService )
    {
        $this->userService = $userService;
        return $this;
    }

    /**
     * @return UserService
     */
    public function getUserService()
    {
        return $this->userService;
    }
}

