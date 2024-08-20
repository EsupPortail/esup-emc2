<?php

namespace Formation\Assertion;

use Formation\Service\Session\SessionService;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenUtilisateur\Service\User\UserService;

class SessionAssertionFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SessionAssertion
    {
        /**
         * @var SessionService $sessionService
         * @var PrivilegeService $privilegeService
         * @var UserService $userService
         */
        $sessionService = $container->get(SessionService::class);
        $privilegeService = $container->get(PrivilegeService::class);
        $userService = $container->get(UserService::class);

        $assertion = new SessionAssertion();
        $assertion->setSessionService($sessionService);
        $assertion->setPrivilegeService($privilegeService);
        $assertion->setUserService($userService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);
        return $assertion;
    }
}