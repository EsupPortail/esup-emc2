<?php

namespace Application\Form\EntretienProfessionnel;

use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use Utilisateur\Service\Role\RoleService;
use Utilisateur\Service\User\UserService;

class EntretienProfessionnelFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var RoleService $roleService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $roleService = $container->get(RoleService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var EntretienProfessionnelHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(EntretienProfessionnelHydrator::class);

        /**
         * @var EntretienProfessionnelForm $form
         */
        $form = new EntretienProfessionnelForm();
        $form->setAgentService($agentService);
        $form->setRoleService($roleService);
        $form->setUserService($userService);
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}