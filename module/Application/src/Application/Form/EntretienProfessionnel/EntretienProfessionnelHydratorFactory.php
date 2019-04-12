<?php

namespace Application\Form\EntretienProfessionnel;

use Utilisateur\Service\User\UserService;
use Zend\Stdlib\Hydrator\HydratorPluginManager;

class EntretienProfessionnelHydratorFactory {

    public function __invoke(HydratorPluginManager $manager)
    {
        /**
         * @var UserService $userService
         */
        $userService = $manager->getServiceLocator()->get(UserService::class);

        /**
         * @var EntretienProfessionnelHydrator $hydrator
         */
        $hydrator = new EntretienProfessionnelHydrator();
        $hydrator->setUserService($userService);

        return $hydrator;
    }
}