<?php

namespace Application\Controller;

use Application\Form\AgentCompetence\AgentCompetenceForm;
use Application\Form\AgentFormation\AgentFormationForm;
use Application\Form\ApplicationElement\ApplicationElementForm;
use Application\Form\CompetenceElement\CompetenceElementForm;
use Application\Service\Agent\AgentService;
use Application\Service\ApplicationElement\ApplicationElementService;
use Application\Service\Categorie\CategorieService;
use Application\Service\CompetenceElement\CompetenceElementService;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Application\Service\HasApplicationCollection\HasApplicationCollectionService;
use Application\Service\HasCompetenceCollection\HasCompetenceCollectionService;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
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
         * @var HasApplicationCollectionService $hasApplicationCollectionService
         * @var CategorieService $categorieService
         * @var CompetenceElementService $competenceElementService
         * @var HasCompetenceCollectionService $hasCompetenceCollectionService
         * @var EntretienProfessionnelService $entretienService
         * @var ParcoursDeFormationService $parcoursService
         * @var ValidationInstanceService $validationInstanceService
         * @var ValidationTypeService $validationTypeService

         * @var NatureService $natureService
         * @var FichierService $fichierService
         * @var FormationService $formationService
         * @var StructureService $structureService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $applicationElementService = $container->get(ApplicationElementService::class);
        $hasApplicationCollectionService = $container->get(HasApplicationCollectionService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $hasCompetenceCollectionService = $container->get(HasCompetenceCollectionService::class);
        $categorieService = $container->get(CategorieService::class);
        $entretienService = $container->get(EntretienProfessionnelService::class);
        $parcoursService = $container->get(ParcoursDeFormationService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);
        $natureService = $container->get(NatureService::class);
        $fichierService = $container->get(FichierService::class);
        $formationService = $container->get(FormationService::class);
        $userService = $container->get(UserService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var ApplicationElementForm $applicationElementForm
         * @var CompetenceElementForm $competenceElementForm
         * @var AgentCompetenceForm $agentCompetenceForm
         * @var AgentFormationForm $agentFormationForm
         * @var UploadForm $uploadForm
         */
        $applicationElementForm = $container->get('FormElementManager')->get(ApplicationElementForm::class);
        $competenceElementForm = $container->get('FormElementManager')->get(CompetenceElementForm::class);
        $agentCompetenceForm = $container->get('FormElementManager')->get(AgentCompetenceForm::class);
        $agentFormationForm = $container->get('FormElementManager')->get(AgentFormationForm::class);
        $uploadForm = $container->get('FormElementManager')->get(UploadForm::class);

        /** @var AgentController $controller */
        $controller = new AgentController();

        $controller->setAgentService($agentService);
        $controller->setApplicationElementService($applicationElementService);
        $controller->setCategorieService($categorieService);
        $controller->setParcoursDeFormationService($parcoursService);
        $controller->setHasApplicationCollectionService($hasApplicationCollectionService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setHasCompetenceCollectionService($hasCompetenceCollectionService);
        $controller->setEntretienProfessionnelService($entretienService);
        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setValidationTypeService($validationTypeService);
        $controller->setNatureService($natureService);
        $controller->setFichierService($fichierService);
        $controller->setFormationService($formationService);
        $controller->setUserService($userService);
        $controller->setStructureService($structureService);

        $controller->setApplicationElementForm($applicationElementForm);
        $controller->setCompetenceElementForm($competenceElementForm);
        $controller->setAgentCompetenceForm($agentCompetenceForm);
        $controller->setAgentFormationForm($agentFormationForm);
        $controller->setUploadForm($uploadForm);

        return $controller;
    }
}