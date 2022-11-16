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
use Formation\Service\DemandeExterne\DemandeExterneService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Form\AjouterGestionnaire\AjouterGestionnaireForm;
use Structure\Form\AjouterResponsable\AjouterResponsableForm;
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
         * @var DemandeExterneService $demandeService
         * @var FichePosteService $fichePosteService
         * @var FicheProfilService $ficheProfilService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var SpecificitePosteService $specificiteService
         * @var StructureService $structureService
         * @var StructureAgentForceService $structureAgentForceService
         * @var UserService $userService
         *
         * @var SourceService $sourceService
         */
        $agentService = $container->get(AgentService::class);
        $agentMissionSpecifiqueService = $container->get(AgentMissionSpecifiqueService::class);
        $entretienService = $container->get(EntretienProfessionnelService::class);
        $campagneService = $container->get(CampagneService::class);
        $etatService = $container->get(EtatService::class);
        $demandeService = $container->get(DemandeExterneService::class);
        $delegueService = $container->get(DelegueService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $ficheProfilService = $container->get(FicheProfilService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $specificiteService = $container->get(SpecificitePosteService::class);
        $structureService = $container->get(StructureService::class);
        $structureAgentForceService = $container->get(StructureAgentForceService::class);
        $userService = $container->get(UserService::class);

        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $sourceService = $container->get(SourceService::class);
        $sourceService->setEntityManager($entityManager);

        /**
         * @var AgentMissionSpecifiqueForm $affectationForm
         * @var AjouterGestionnaireForm $ajouterGestionnaire
         * @var AjouterResponsableForm $ajouterResponsable
         * @var SelectionAgentForm $selectionAgentForm
         * @var HasDescriptionFormAwareTrait $hasDescriptionForm
         */
        $affectationForm = $container->get('FormElementManager')->get(AgentMissionSpecifiqueForm::class);
        $ajouterGestionnaire = $container->get('FormElementManager')->get(AjouterGestionnaireForm::class);
        $ajouterResponsable = $container->get('FormElementManager')->get(AjouterResponsableForm::class);
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
        $controller->setDemandeExterneService($demandeService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setFicheProfilService($ficheProfilService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setSpecificitePosteService($specificiteService);
        $controller->setStructureService($structureService);
        $controller->setStructureAgentForceService($structureAgentForceService);
        $controller->setUserService($userService);
        $controller->setSourceService($sourceService);

        $controller->setAgentMissionSpecifiqueForm($affectationForm);
        $controller->setAjouterGestionnaireForm($ajouterGestionnaire);
        $controller->setAjouterResponsableForm($ajouterResponsable);
        $controller->setSelectionAgentForm($selectionAgentForm);
        $controller->setHasDescriptionForm($hasDescriptionForm);

        $controller->setRenderer($container->get('ViewRenderer'));
        return $controller;
    }
}