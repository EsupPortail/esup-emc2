<?php

namespace Application\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\FichePoste\FichePosteService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Formation\Service\DemandeExterne\DemandeExterneService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenAuthentification\Service\UserContext;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class IndexControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return IndexController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : IndexController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var AgentService $agentService
         * @var CampagneService $campagneService
         * @var RenduService $renduService
         * @var RoleService $roleService
         * @var StructureService $structureService
         * @var UserService $userService
         * @var UserContext $userContext
         *
         * @var FichePosteService $fichePosteService
         * @var EntretienProfessionnelService $entretienProfessionelService
         * @var FormationInstanceInscritService $formationInstanceinscritService
         * @var DemandeExterneService $demandeExterneService
         *
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $campagneService = $container->get(CampagneService::class);
        $renduService = $container->get(RenduService::class);
        $roleService = $container->get(RoleService::class);
        $userService = $container->get(UserService::class);
        $userContext = $container->get(UserContext::class);
        $structureService = $container->get(StructureService::class);

        $fichePosteService = $container->get(FichePosteService::class);
        $entretienProfessionelService = $container->get(EntretienProfessionnelService::class);
        $formationInstanceinscritService = $container->get(FormationInstanceInscritService::class);
        $demandeExterneService = $container->get(DemandeExterneService::class);

        /** @var IndexController $controller */
        $controller = new IndexController();
        $controller->setAgentService($agentService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setAgentService($agentService);
        $controller->setCampagneService($campagneService);
        $controller->setRenduService($renduService);
        $controller->setRoleService($roleService);
        $controller->setServiceUserContext($userContext);
        $controller->setStructureService($structureService);
        $controller->setUserService($userService);

        $controller->setFichePosteService($fichePosteService);
        $controller->setEntretienProfessionnelService($entretienProfessionelService);
        $controller->setFormationInstanceInscritService($formationInstanceinscritService);
        $controller->setDemandeExterneService($demandeExterneService);
        return $controller;
    }

}