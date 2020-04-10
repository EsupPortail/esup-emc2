<?php

namespace Application\Assertion;

use Application\Service\FichePoste\FichePosteService;
use Application\Service\Structure\StructureService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;

class FichePosteAssertionFactory
{
    /**
     * @param ContainerInterface $container
     * @return FichePosteAssertion
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FichePosteService $fichePosteService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $fichePosteService = $container->get(FichePosteService::class);
        $structureService = $container->get(StructureService::class);
        $userService = $container->get(UserService::class);

        /** @var FichePosteAssertion $assertion */
        $assertion = new FichePosteAssertion();
        $assertion->setFichePosteService($fichePosteService);
//        $assertion->setStructureService($structureService);
        $assertion->setUserService($userService);
        return $assertion;
    }

}