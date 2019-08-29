<?php

namespace Application\Assertion;

use Utilisateur\Service\User\UserService;
use Zend\ServiceManager\ServiceLocatorInterface;

class EditionStructureAssertionFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var UserService $userService
         */
        $userService = $serviceLocator->get(UserService::class);

        /** @var EditionStructureAssertion $assertion */
        $assertion = new EditionStructureAssertion();
        $assertion->setUserService($userService);
        return $assertion;
    }

}