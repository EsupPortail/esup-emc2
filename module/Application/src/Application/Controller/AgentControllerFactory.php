<?php

namespace Application\Controller;

use Application\Form\AgentApplication\AgentApplicationForm;
use Application\Form\AgentCompetence\AgentCompetenceForm;
use Application\Form\AgentFormation\AgentFormationForm;
use Application\Service\Agent\AgentService;
use Application\Service\ApplicationElement\ApplicationElementService;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Application\Service\HasApplicationCollection\HasApplicationCollectionService;
use Application\Service\Structure\StructureService;
use Fichier\Form\Upload\UploadForm;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Nature\NatureService;
use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class AgentControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var ApplicationElementService $applicationElementService
         * @var EntretienProfessionnelService $entretienService
         * @var ValidationInstanceService $validationInstanceService
         * @var ValidationTypeService $validationTypeService
         * @var HasApplicationCollectionService $hasApplicationCollectionService
         * @var NatureService $natureService
         * @var FichierService $fichierService
         * @var FormationService $formationService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $applicationElementService = $container->get(ApplicationElementService::class);
        $entretienService = $container->get(EntretienProfessionnelService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);
        $hasApplicationCollectionService = $container->get(HasApplicationCollectionService::class);
        $natureService = $container->get(NatureService::class);
        $fichierService = $container->get(FichierService::class);
        $formationService = $container->get(FormationService::class);
        $userService = $container->get(UserService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var AgentApplicationForm $agentApplicationForm
         * @var AgentCompetenceForm $agentCompetenceForm
         * @var AgentFormationForm $agentFormationForm
         * @var UploadForm $uploadForm
         */
        $agentApplicationForm = $container->get('FormElementManager')->get(AgentApplicationForm::class);
        $agentCompetenceForm = $container->get('FormElementManager')->get(AgentCompetenceForm::class);
        $agentFormationForm = $container->get('FormElementManager')->get(AgentFormationForm::class);
        $uploadForm = $container->get('FormElementManager')->get(UploadForm::class);

        /** @var AgentController $controller */
        $controller = new AgentController();

        $controller->setAgentService($agentService);
        $controller->setApplicationElementService($applicationElementService);
        $controller->setEntretienProfessionnelService($entretienService);
        $controller->setHasApplicationCollectionService($hasApplicationCollectionService);
        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setValidationTypeService($validationTypeService);
        $controller->setNatureService($natureService);
        $controller->setFichierService($fichierService);
        $controller->setFormationService($formationService);
        $controller->setUserService($userService);
        $controller->setStructureService($structureService);

        $controller->setAgentApplicationForm($agentApplicationForm);
        $controller->setAgentCompetenceForm($agentCompetenceForm);
        $controller->setAgentFormationForm($agentFormationForm);
        $controller->setUploadForm($uploadForm);

        return $controller;
    }
}