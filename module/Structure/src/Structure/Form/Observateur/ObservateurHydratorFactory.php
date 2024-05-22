<?php

namespace Structure\Form\Observateur;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenUtilisateur\Service\User\UserService;

class ObservateurHydratorFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ObservateurHydrator
    {
        /**
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        $hydrator = new ObservateurHydrator();
        $hydrator->setStructureService($structureService);
        $hydrator->setUserService($userService);
        return $hydrator;
    }
}