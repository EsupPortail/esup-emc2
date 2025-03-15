<?php

namespace Structure\Controller;

use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Form\HasDescription\HasDescriptionForm;
use Application\Form\HasDescription\HasDescriptionFormAwareTrait;
use Application\Form\SelectionAgent\SelectionAgentForm;
use Application\Service\Agent\AgentService;
use Agent\Service\AgentAffectation\AgentAffectationService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\SpecificitePoste\SpecificitePosteService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Structure\StructureService;
use Structure\Service\StructureAgentForce\StructureAgentForceService;
use Structure\Service\Type\TypeService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenParametre\Service\Parametre\ParametreService;

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
         * @var AgentAffectationService $agentAffectationService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentMissionSpecifiqueService $agentMissionSpecifiqueService
         * @var AgentSuperieurService $agentSuperieurService
         * @var EntretienProfessionnelService $entretienService
         * @var EtatTypeService $etatTypeService
         * @var CampagneService $campagneService
         * @var FichePosteService $fichePosteService
         * @var ObservateurService $observateurService
         * @var ParametreService $parametreService
         * @var SpecificitePosteService $specificiteService
         * @var StructureService $structureService
         * @var StructureAgentForceService $structureAgentForceService
         * @var TypeService $typeService
         */
        $agentService = $container->get(AgentService::class);
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentMissionSpecifiqueService = $container->get(AgentMissionSpecifiqueService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $entretienService = $container->get(EntretienProfessionnelService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $campagneService = $container->get(CampagneService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $observateurService = $container->get(ObservateurService::class);
        $parametreService = $container->get(ParametreService::class);
        $specificiteService = $container->get(SpecificitePosteService::class);
        $structureService = $container->get(StructureService::class);
        $structureAgentForceService = $container->get(StructureAgentForceService::class);
        $typeService = $container->get(TypeService::class);

        /**
         * @var AgentMissionSpecifiqueForm $affectationForm
         * @var SelectionAgentForm $selectionAgentForm
         * @var HasDescriptionFormAwareTrait $hasDescriptionForm
         */
        $affectationForm = $container->get('FormElementManager')->get(AgentMissionSpecifiqueForm::class);
        $selectionAgentForm = $container->get('FormElementManager')->get(SelectionAgentForm::class);
        $hasDescriptionForm = $container->get('FormElementManager')->get(HasDescriptionForm::class);

        $controller = new StructureController();

        $controller->setAgentService($agentService);
        $controller->setAgentAffectationService($agentAffectationService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentMissionSpecifiqueService($agentMissionSpecifiqueService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setEntretienProfessionnelService($entretienService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setCampagneService($campagneService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setObservateurService($observateurService);
        $controller->setParametreService($parametreService);
        $controller->setSpecificitePosteService($specificiteService);
        $controller->setStructureService($structureService);
        $controller->setStructureAgentForceService($structureAgentForceService);
        $controller->setTypeService($typeService);

        $controller->setAgentMissionSpecifiqueForm($affectationForm);
        $controller->setSelectionAgentForm($selectionAgentForm);
        $controller->setHasDescriptionForm($hasDescriptionForm);

        $controller->setRenderer($container->get('ViewRenderer'));
        return $controller;
    }
}