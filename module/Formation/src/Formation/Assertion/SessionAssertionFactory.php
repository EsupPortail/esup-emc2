<?php

namespace Formation\Assertion;

use Formation\Service\FormationInstance\FormationInstanceService;
use Laminas\Mvc\Application;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenPrivilege\Service\Privilege\PrivilegeCategorieService;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenUtilisateur\Service\User\UserService;

class SessionAssertionFactory
{
    /**
     * @param ContainerInterface $container
     * @return SessionAssertion
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SessionAssertion
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var PrivilegeCategorieService $privilegeCategorieService
         * @var PrivilegeService $privilegeService
         * @var UserService $userService
 */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $privilegeCategorieService = $container->get(PrivilegeCategorieService::class);
        $privilegeService = $container->get(PrivilegeService::class);
        $userService = $container->get(UserService::class);

        $assertion = new SessionAssertion();
        $assertion->setFormationInstanceService($formationInstanceService);
        $assertion->setPrivilegeCategorieService($privilegeCategorieService);
        $assertion->setPrivilegeService($privilegeService);
        $assertion->setUserService($userService);

        /* @var $application Application */
        $application = $container->get('Application');
        $mvcEvent = $application->getMvcEvent();
        $assertion->setMvcEvent($mvcEvent);
        return $assertion;
    }
}