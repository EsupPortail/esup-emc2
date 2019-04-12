<?php

namespace Application\Form\EntretienProfessionnel;

use Application\Service\Role\RoleService;
use Utilisateur\Service\User\UserService;
use Zend\Form\FormElementManager;

class EntretienProfessionnelFormFactory {

    public function __invoke(FormElementManager $manager)
    {
        /**
         * @var RoleService $roleService
         * @var UserService $userService
         */
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
        $form->setRoleService($roleService);
        $form->setUserService($userService);
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}