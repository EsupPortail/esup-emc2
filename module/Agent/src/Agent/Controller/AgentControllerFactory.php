<?php

namespace Agent\Controller;

use Agent\Service\Agent\AgentService;
use Agent\Service\AgentAffectation\AgentAffectationService;
use Agent\Service\AgentAutorite\AgentAutoriteService;
use Agent\Service\AgentGrade\AgentGradeService;
use Agent\Service\AgentStatut\AgentStatutService;
use Agent\Service\AgentSuperieur\AgentSuperieurService;
use Agent\Assertion\ChaineAssertion;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenFichier\Form\Upload\UploadForm;
use UnicaenFichier\Service\Fichier\FichierService;
use UnicaenFichier\Service\Nature\NatureService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;

class AgentControllerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentAffectationService $agentAffectationService
         * @var AgentGradeService $agentGradeService
         * @var AgentMissionSpecifiqueService $agentMissionSpecifiqueService
         * @var AgentSuperieurService $agentSuperieurService
         * @var CampagneService $campagneService
         * @var FichierService $fichierService
         * @var NatureService $natureService
         * @var ParametreService $parametreService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $agentGradeService = $container->get(AgentGradeService::class);
        $agentMissionSpecifiqueService = $container->get(AgentMissionSpecifiqueService::class);
        $agentStatutService = $container->get(AgentStatutService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $campagneService = $container->get(CampagneService::class);
        $fichierService = $container->get(FichierService::class);
        $natureService = $container->get(NatureService::class);
        $parametreService = $container->get(ParametreService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var UploadForm $uploadForm
         */
        $uploadForm = $container->get('FormElementManager')->get(UploadForm::class);

        $controller = new AgentController();
        $controller->setAgentService($agentService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentAffectationService($agentAffectationService);
        $controller->setAgentGradeService($agentGradeService);
        $controller->setAgentMissionSpecifiqueService($agentMissionSpecifiqueService);
        $controller->setAgentStatutService($agentStatutService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setCampagneService($campagneService);
        $controller->setFichierService($fichierService);
        $controller->setNatureService($natureService);
        $controller->setParametreService($parametreService);
        $controller->setUserService($userService);

        $controller->setUploadForm($uploadForm);

        $assertion = $container->get(ChaineAssertion::class);
        $controller->chaineAssertion = $assertion;
        return $controller;
    }
}
