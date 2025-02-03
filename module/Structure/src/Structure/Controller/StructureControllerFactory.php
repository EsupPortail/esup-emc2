<?php

namespace Structure\Controller;

use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueForm;
use Application\Form\HasDescription\HasDescriptionForm;
use Application\Form\HasDescription\HasDescriptionFormAwareTrait;
use Application\Form\SelectionAgent\SelectionAgentForm;
use Application\Service\Agent\AgentService;
use Agent\Service\AgentAffectation\AgentAffectationService;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use FichePoste\Service\FichePoste\FichePosteService;
use Application\Service\SpecificitePoste\SpecificitePosteService;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Observateur\ObservateurService;
use Structure\Service\Structure\StructureService;
use Structure\Service\StructureAgentForce\StructureAgentForceService;
use UnicaenContact\Form\Contact\ContactForm;
use UnicaenContact\Service\Contact\ContactService;
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
         * @var AgentMissionSpecifiqueService $agentMissionSpecifiqueService
         * @var ContactService $contactService
         * @var EntretienProfessionnelService $entretienService
         * @var EtatTypeService $etatTypeService
         * @var CampagneService $campagneService
         * @var FichePosteService $fichePosteService
         * @var ObservateurService $observateurService
         * @var ParametreService $parametreService
         * @var SpecificitePosteService $specificiteService
         * @var StructureService $structureService
         * @var StructureAgentForceService $structureAgentForceService
         */
        $agentService = $container->get(AgentService::class);
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $agentMissionSpecifiqueService = $container->get(AgentMissionSpecifiqueService::class);
        $contactService = $container->get(ContactService::class);
        $entretienService = $container->get(EntretienProfessionnelService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $campagneService = $container->get(CampagneService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $observateurService = $container->get(ObservateurService::class);
        $parametreService = $container->get(ParametreService::class);
        $specificiteService = $container->get(SpecificitePosteService::class);
        $structureService = $container->get(StructureService::class);
        $structureAgentForceService = $container->get(StructureAgentForceService::class);

        /**
         * @var AgentMissionSpecifiqueForm $affectationForm
         * @var ContactForm $contactForm
         * @var SelectionAgentForm $selectionAgentForm
         * @var HasDescriptionFormAwareTrait $hasDescriptionForm
         */
        $affectationForm = $container->get('FormElementManager')->get(AgentMissionSpecifiqueForm::class);
        $contactForm = $container->get('FormElementManager')->get(ContactForm::class);
        $selectionAgentForm = $container->get('FormElementManager')->get(SelectionAgentForm::class);
        $hasDescriptionForm = $container->get('FormElementManager')->get(HasDescriptionForm::class);

        $controller = new StructureController();

        $controller->setAgentService($agentService);
        $controller->setAgentAffectationService($agentAffectationService);
        $controller->setAgentMissionSpecifiqueService($agentMissionSpecifiqueService);
        $controller->setContactService($contactService);
        $controller->setEntretienProfessionnelService($entretienService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setCampagneService($campagneService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setObservateurService($observateurService);
        $controller->setParametreService($parametreService);
        $controller->setSpecificitePosteService($specificiteService);
        $controller->setStructureService($structureService);
        $controller->setStructureAgentForceService($structureAgentForceService);

        $controller->setAgentMissionSpecifiqueForm($affectationForm);
        $controller->setContactForm($contactForm);
        $controller->setSelectionAgentForm($selectionAgentForm);
        $controller->setHasDescriptionForm($hasDescriptionForm);

        $controller->setRenderer($container->get('ViewRenderer'));
        return $controller;
    }
}