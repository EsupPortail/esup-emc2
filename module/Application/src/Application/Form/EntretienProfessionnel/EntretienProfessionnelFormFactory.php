<?php

namespace Application\Form\EntretienProfessionnel;

use Application\Service\Agent\AgentService;
use Utilisateur\Service\Role\RoleService;
use Utilisateur\Service\User\UserService;
use Zend\Form\FormElementManager;

class EntretienProfessionnelFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /**
         * @var AgentService $agentService
         * @var RoleService $roleService
         * @var UserService $userService
         */
        $agentService = $manager->getServiceLocator()->get(AgentService::class);
        $roleService = $manager->getServiceLocator()->get(RoleService::class);
        $userService = $manager->getServiceLocator()->get(UserService::class);

        /**
         * @var EntretienProfessionnelHydrator $hydrator
         */
        $hydrator = $manager->getServiceLocator()->get('HydratorManager')->get(EntretienProfessionnelHydrator::class);

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