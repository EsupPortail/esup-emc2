<?php

namespace Application\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAccompagnement\AgentAccompagnementService;
use Application\Service\AgentAffectation\AgentAffectationService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentGrade\AgentGradeService;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use Application\Service\AgentMobilite\AgentMobiliteService;
use Application\Service\AgentPPP\AgentPPPService;
use Application\Service\AgentQuotite\AgentQuotiteService;
use Application\Service\AgentStageObservation\AgentStageObservationService;
use Application\Service\AgentStatut\AgentStatutService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\AgentTutorat\AgentTutoratService;
use Application\Service\FichePoste\FichePosteService;
use Carriere\Service\Categorie\CategorieService;
use Element\Form\ApplicationElement\ApplicationElementForm;
use Element\Form\CompetenceElement\CompetenceElementForm;
use Element\Service\Application\ApplicationService;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\CompetenceElement\CompetenceElementService;
use Element\Service\HasApplicationCollection\HasApplicationCollectionService;
use Element\Service\HasCompetenceCollection\HasCompetenceCollectionService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Fichier\Form\Upload\UploadForm;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Nature\NatureService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class AgentControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return AgentController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentController
    {
        /**
         * @var ApplicationElementService $applicationElementService
         * @var HasApplicationCollectionService $hasApplicationCollectionService
         * @var CategorieService $categorieService
         * @var CompetenceElementService $competenceElementService
         * @var HasCompetenceCollectionService $hasCompetenceCollectionService
         * @var ParametreService $parametreService
         * @var ValidationInstanceService $validationInstanceService
         * @var ValidationTypeService $validationTypeService
         * @var NatureService $natureService
         * @var FichierService $fichierService
         * @var ApplicationService $applicationService
         * @var StructureService $structureService
         * @var UserService $userService
         */

        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var AgentAffectationService $agentAffectationService
         * @var AgentGradeService $agentGradeService
         * @var AgentMissionSpecifiqueService $agentMissionSpecifiqueService
         * @var AgentMobiliteService $agentMobiliteService
         * @var AgentQuotiteService $agentQuotiteService
         * @var AgentStatutService $agentStatutService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var FichePosteService $fichePosteService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $agentGradeService = $container->get(AgentGradeService::class);
        $agentMissionSpecifiqueService = $container->get(AgentMissionSpecifiqueService::class);
        $agentMobiliteService = $container->get(AgentMobiliteService::class);
        $agentQuotiteService = $container->get(AgentQuotiteService::class);
        $agentStatutService = $container->get(AgentStatutService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $fichePosteService = $container->get(FichePosteService::class);
        $parametreService = $container->get(ParametreService::class);
        $userService = $container->get(UserService::class);

        $applicationElementService = $container->get(ApplicationElementService::class);
        $hasApplicationCollectionService = $container->get(HasApplicationCollectionService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $hasCompetenceCollectionService = $container->get(HasCompetenceCollectionService::class);
        $categorieService = $container->get(CategorieService::class);

        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);
        $natureService = $container->get(NatureService::class);
        $fichierService = $container->get(FichierService::class);
        $applicationService = $container->get(ApplicationService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var ApplicationElementForm $applicationElementForm
         * @var CompetenceElementForm $competenceElementForm
         * @var UploadForm $uploadForm
         */
        $applicationElementForm = $container->get('FormElementManager')->get(ApplicationElementForm::class);
        $competenceElementForm = $container->get('FormElementManager')->get(CompetenceElementForm::class);
        $uploadForm = $container->get('FormElementManager')->get(UploadForm::class);

        $controller = new AgentController();

        $controller->setAgentService($agentService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setAgentService($agentService);
        $controller->setAgentAffectationService($agentAffectationService);
        $controller->setAgentGradeService($agentGradeService);
        $controller->setAgentMissionSpecifiqueService($agentMissionSpecifiqueService);
        $controller->setAgentMobiliteService($agentMobiliteService);
        $controller->setAgentQuotiteService($agentQuotiteService);
        $controller->setAgentStatutService($agentStatutService);
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setFichePosteService($fichePosteService);
        $controller->setParametreService($parametreService);
        $controller->setUserService($userService);

        $controller->setApplicationElementService($applicationElementService);
        $controller->setHasApplicationCollectionService($hasApplicationCollectionService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setHasCompetenceCollectionService($hasCompetenceCollectionService);

        $controller->setCategorieService($categorieService);

        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setValidationTypeService($validationTypeService);
        $controller->setNatureService($natureService);
        $controller->setFichierService($fichierService);
        $controller->setApplicationService($applicationService);

        $controller->setStructureService($structureService);

        $controller->setApplicationElementForm($applicationElementForm);
        $controller->setCompetenceElementForm($competenceElementForm);
        $controller->setUploadForm($uploadForm);

        /**
         * @var AgentPPPService $agentPppService
         * @var AgentStageObservationService $agentStageObservationService
         * @var AgentTutoratService $agentTutoratService
         * @var AgentAccompagnementService $agentAccompagnementService
         */
        $agentPppService = $container->get(AgentPPPService::class);
        $agentStageObservationService = $container->get(AgentStageObservationService::class);
        $agentTutoratService = $container->get(AgentTutoratService::class);
        $agentAccompagnementService = $container->get(AgentAccompagnementService::class);

        $controller->setAgentPPPService($agentPppService);
        $controller->setAgentStageObservationService($agentStageObservationService);
        $controller->setAgentTutoratService($agentTutoratService);
        $controller->setAgentAccompagnementService($agentAccompagnementService);

        return $controller;
    }
}