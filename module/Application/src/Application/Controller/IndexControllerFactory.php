<?php

namespace Application\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\Macro\MacroService;
use Application\Service\Url\UrlService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use FichePoste\Service\FichePoste\FichePosteService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenAuthentification\Service\UserContext;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;

class IndexControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return IndexController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): IndexController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentMissionSpecifiqueService $agentMissionSpecifiqueService
         * @var AgentSuperieurService $agentSuperieurService
         * @var AgentService $agentService
         * @var CampagneService $campagneService
         * @var MacroService $macroService
         * @var ParametreService $parametreService
         * @var RenduService $renduService
         * @var RoleService $roleService
         * @var StructureService $structureService
         * @var UserService $userService
         * @var UserContext $userContext
         * @var UrlService $urlService
         *
         * @var FichePosteService $fichePosteService
         * @var EntretienProfessionnelService $entretienProfessionelService
         *
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentMissionSpecifiqueService = $container->get(AgentMissionSpecifiqueService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $campagneService = $container->get(CampagneService::class);
        $macroService = $container->get(MacroService::class);
        $parametreService = $container->get(ParametreService::class);
        $renduService = $container->get(RenduService::class);
        $roleService = $container->get(RoleService::class);
        $userService = $container->get(UserService::class);
        $userContext = $container->get(UserContext::class);
        $urlService = $container->get(UrlService::class);
        $structureService = $container->get(StructureService::class);

        $fichePosteService = $container->get(FichePosteService::class);
        $entretienProfessionelService = $container->get(EntretienProfessionnelService::class);

        $controller = new IndexController();
        $controller->setAgentService($agentService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentMissionSpecifiqueService($agentMissionSpecifiqueService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setAgentService($agentService);
        $controller->setCampagneService($campagneService);
        $controller->setMacroService($macroService);
        $controller->setParametreService($parametreService);
        $controller->setRenduService($renduService);
        $controller->setRoleService($roleService);
        $controller->setServiceUserContext($userContext);
        $controller->setStructureService($structureService);
        $controller->setUserService($userService);
        $controller->setUrlService($urlService);

        $controller->setFichePosteService($fichePosteService);
        $controller->setEntretienProfessionnelService($entretienProfessionelService);
        return $controller;
    }

}