<?php

namespace Structure\Controller;

use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Form\HasDescription\HasDescriptionForm;
use Application\Form\HasDescription\HasDescriptionFormAwareTrait;
use Application\Form\SelectionAgent\SelectionAgentForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\FicheProfil\FicheProfilService;
use Application\Service\SpecificitePoste\SpecificitePosteService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\Delegue\DelegueService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\Synchronisation\SynchronisationService;
use Structure\Service\Structure\StructureService;
use Structure\Service\StructureAgentForce\StructureAgentForceService;
use UnicaenDbImport\Entity\Db\Service\Source\SourceService;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenUtilisateur\Service\User\UserService;

class StructureControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return StructureController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : StructureController
    {
        /**
         * @var AgentService $agentService
         * @var AgentMissionSpecifiqueService $agentMissionSpecifiqueService
         * @var EntretienProfessionnelService $entretienService
         * @var CampagneService $campagneService
         * @var EtatService $etatService
         * @var DelegueService $delegueService
         * @var FichePosteService $fichePosteService
         * @var FicheProfilService $ficheProfilService
         * @var SpecificitePosteService $specificiteService
         * @var StructureService $structureService
         * @var StructureAgentForceService $structureAgentForceService
         * @var UserService $userService
         *
         * @var SourceService $sourceService
         * @var SynchronisationService $synchronisationService
         */
        $agentService = $container->get(AgentService::class);
        $agentMissionSpecifiqueService = $container->get(AgentMissionSpecifiqueService::class);
        $entretienService = $container->get(EntretienProfessionnelService::class);
        $campagneService = $container->get(CampagneService::class);
        $etatService = $container->get(EtatService::class);
        $delegueService = $container->get(DelegueService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $ficheProfilService = $container->get(FicheProfilService::class);
        $specificiteService = $container->get(SpecificitePosteService::class);
        $structureService = $container->get(StructureService::class);
        $structureAgentForceService = $container->get(StructureAgentForceService::class);
        $userService = $container->get(UserService::class);
        $synchronisationService = $container->get(SynchronisationService::class);

        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sourceService = $container->get(SourceService::class);
        $sourceService->setEntityManager($entityManager);

        /**
         * @var AgentMissionSpecifiqueForm $affectationForm
         * @var SelectionAgentForm $selectionAgentForm
         * @var HasDescriptionFormAwareTrait $hasDescriptionForm
         */
        $affectationForm = $container->get('FormElementManager')->get(AgentMissionSpecifiqueForm::class);
        $selectionAgentForm = $container->get('FormElementManager')->get(SelectionAgentForm::class);
        $hasDescriptionForm = $container->get('FormElementManager')->get(HasDescriptionForm::class);

        /** @var StructureController $controller */
        $controller = new StructureController();

        $controller->setAgentService($agentService);
        $controller->setAgentMissionSpecifiqueService($agentMissionSpecifiqueService);
        $controller->setEntretienProfessionnelService($entretienService);
        $controller->setCampagneService($campagneService);
        $controller->setEtatService($etatService);
        $controller->setDelegueService($delegueService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setFicheProfilService($ficheProfilService);
        $controller->setSpecificitePosteService($specificiteService);
        $controller->setStructureService($structureService);
        $controller->setStructureAgentForceService($structureAgentForceService);
        $controller->setUserService($userService);
        $controller->setSourceService($sourceService);
        $controller->setSynchronisationService($synchronisationService);

        $controller->setAgentMissionSpecifiqueForm($affectationForm);
        $controller->setSelectionAgentForm($selectionAgentForm);
        $controller->setHasDescriptionForm($hasDescriptionForm);

        $controller->setRenderer($container->get('ViewRenderer'));
        return $controller;
    }
}