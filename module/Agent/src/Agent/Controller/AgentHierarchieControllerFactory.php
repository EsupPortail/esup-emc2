<?php

namespace Agent\Controller;

use Agent\Form\AgentHierarchieCalcul\AgentHierarchieCalculForm;
use Agent\Form\AgentHierarchieImportation\AgentHierarchieImportationForm;
use Agent\Service\Agent\AgentService;
use Agent\Service\AgentAffectation\AgentAffectationService;
use Agent\Service\AgentAutorite\AgentAutoriteService;
use Agent\Service\AgentGrade\AgentGradeService;
use Agent\Service\AgentRef\AgentRefService;
use Agent\Service\AgentStatut\AgentStatutService;
use Agent\Service\AgentSuperieur\AgentSuperieurService;
use Agent\Form\Chaine\ChaineForm;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\Url\UrlService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenRenderer\Service\Template\TemplateService;
use UnicaenUtilisateur\Service\User\UserService;

class AgentHierarchieControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return AgentHierarchieController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentHierarchieController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentRefService $agentRefService
         * @var AgentSuperieurService $agentSuperieurService
         * @var ParametreService $parametreService
         * @var StructureService $structureService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentRefService = $container->get(AgentRefService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $parametreService = $container->get(ParametreService::class);
        $structureService = $container->get(StructureService::class);


        /**
         * @var AgentAffectationService $agentAffectationService
         * @var AgentGradeService $agentGradeService
         * @var AgentMissionSpecifiqueService $agentMissionSpecifiqueService
         * @var AgentStatutService $agentStatutService
         * @var CampagneService $campagneService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var FichePosteService $fichePosteService
         * @var RenduService $renduService
         * @var TemplateService $templateService
         * @var UserService $userService
         * @var UrlService $urlService
         */
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $agentGradeService = $container->get(AgentGradeService::class);
        $agentMissionSpecifiqueService = $container->get(AgentMissionSpecifiqueService::class);
        $agentStatutService = $container->get(AgentStatutService::class);
        $campagneService = $container->get(CampagneService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $renduService = $container->get(RenduService::class);
        $templateService = $container->get(TemplateService::class);
        $userService = $container->get(UserService::class);
        $urlService = $container->get(UrlService::class);

        /**
         * @var AgentHierarchieImportationForm $importationForm
         * @var AgentHierarchieCalculForm $calculForm
         * @var ChaineForm $chaineForm
         */
        $importationForm = $container->get('FormElementManager')->get(AgentHierarchieImportationForm::class);
        $calculForm = $container->get('FormElementManager')->get(AgentHierarchieCalculForm::class);
        $chaineForm = $container->get('FormElementManager')->get(ChaineForm::class);

        $controller = new AgentHierarchieController();
        $controller->setAgentService($agentService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentRefService($agentRefService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setParametreService($parametreService);
        $controller->setStructureService($structureService);

        $controller->setAgentAffectationService($agentAffectationService);
        $controller->setAgentGradeService($agentGradeService);
        $controller->setAgentMissionSpecifiqueService($agentMissionSpecifiqueService);
        $controller->setAgentStatutService($agentStatutService);
        $controller->setCampagneService($campagneService);
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setUrlService($urlService);
        $controller->setRenduService($renduService);
        $controller->setTemplateService($templateService);
        $controller->setUserService($userService);
        $controller->setAgentHierarchieCalculForm($calculForm);
        $controller->setAgentHierarchieImportationForm($importationForm);
        $controller->setChaineForm($chaineForm);
        return $controller;
    }

}