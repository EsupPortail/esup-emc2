<?php

namespace Application\Service\User;

use RuntimeException;

/**
 * Description of UserServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait UserServiceAwareTrait
{
    /**
     * @var UserService
     */
    private $userService;

    /**
     * @param UserService $userService
     * @return self
     */
    public function setUserService( UserService $userService )
    {
        $this->userService = $userService;
        return $this;
    }



    /**
     * @return UserService
     * @throws RuntimeException
     */
    public function getUserService()
    {
        if (empty($this->userService)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
            $this->userService = $serviceLocator->get('UserService');
        }
        return $this->userService;
    }
}

