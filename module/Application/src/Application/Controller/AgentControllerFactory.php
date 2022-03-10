<?php

namespace Application\Controller;

use Application\Form\AgentAccompagnement\AgentAccompagnementForm;
use Application\Form\AgentPPP\AgentPPPForm;
use Application\Form\AgentStageObservation\AgentStageObservationForm;
use Application\Form\AgentTutorat\AgentTutoratForm;
use Application\Form\ApplicationElement\ApplicationElementForm;
use Application\Form\CompetenceElement\CompetenceElementForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentAccompagnement\AgentAccompagnementService;
use Application\Service\AgentAffectation\AgentAffectationService;
use Application\Service\AgentGrade\AgentGradeService;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueService;
use Application\Service\AgentPPP\AgentPPPService;
use Application\Service\AgentQuotite\AgentQuotiteService;
use Application\Service\AgentStageObservation\AgentStageObservationService;
use Application\Service\AgentStatut\AgentStatutService;
use Application\Service\AgentTutorat\AgentTutoratService;
use Application\Service\Application\ApplicationService;
use Application\Service\ApplicationElement\ApplicationElementService;
use Application\Service\CompetenceElement\CompetenceElementService;
use Application\Service\FichePoste\FichePosteService;
use Application\Service\HasApplicationCollection\HasApplicationCollectionService;
use Application\Service\HasCompetenceCollection\HasCompetenceCollectionService;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use Application\Service\Structure\StructureService;
use Carriere\Service\Categorie\CategorieService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Fichier\Form\Upload\UploadForm;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Nature\NatureService;
use Formation\Form\FormationElement\FormationElementForm;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationElement\FormationElementService;
use Formation\Service\HasFormationCollection\HasFormationCollectionService;
use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class AgentControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentController
     */
    public function __invoke(ContainerInterface $container) : AgentController
    {
        /**
         * @var ApplicationElementService $applicationElementService
         * @var HasApplicationCollectionService $hasApplicationCollectionService
         * @var CategorieService $categorieService
         * @var CompetenceElementService $competenceElementService
         * @var HasCompetenceCollectionService $hasCompetenceCollectionService
         * @var FormationElementService $formationElementService
         * @var HasFormationCollectionService $hasFormationElementService
         * @var ParametreService $parametreService
         * @var ParcoursDeFormationService $parcoursService
         * @var ValidationInstanceService $validationInstanceService
         * @var ValidationTypeService $validationTypeService

         * @var NatureService $natureService
         * @var FichierService $fichierService
         * @var ApplicationService $applicationService
         * @var FormationService $formationService
         * @var StructureService $structureService
         * @var UserService $userService
         */

        /**
         * @var AgentService $agentService
         * @var AgentAffectationService $agentAffectationService
         * @var AgentGradeService $agentGradeService
         * @var AgentMissionSpecifiqueService $agentMissionSpecifiqueService
         * @var AgentQuotiteService $agentQuotiteService
         * @var AgentStatutService $agentStatutService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var FichePosteService $fichePosteService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $agentGradeService = $container->get(AgentGradeService::class);
        $agentMissionSpecifiqueService = $container->get(AgentMissionSpecifiqueService::class);
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
        $formationElementService = $container->get(FormationElementService::class);
        $hasFormationElementService = $container->get(HasFormationCollectionService::class);
        $categorieService = $container->get(CategorieService::class);

        $parcoursService = $container->get(ParcoursDeFormationService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);
        $natureService = $container->get(NatureService::class);
        $fichierService = $container->get(FichierService::class);
        $applicationService = $container->get(ApplicationService::class);
        $formationService = $container->get(FormationService::class);
        $structureService = $container->get(StructureService::class);

        /**
         * @var ApplicationElementForm $applicationElementForm
         * @var CompetenceElementForm $competenceElementForm
         * @var FormationElementForm $formationElementForm
         * @var UploadForm $uploadForm
         */
        $applicationElementForm = $container->get('FormElementManager')->get(ApplicationElementForm::class);
        $competenceElementForm = $container->get('FormElementManager')->get(CompetenceElementForm::class);
        $formationElementForm = $container->get('FormElementManager')->get(FormationElementForm::class);
        $uploadForm = $container->get('FormElementManager')->get(UploadForm::class);

        /** @var AgentController $controller */
        $controller = new AgentController();

        $controller->setAgentService($agentService);
        $controller->setAgentAffectationService($agentAffectationService);
        $controller->setAgentGradeService($agentGradeService);
        $controller->setAgentMissionSpecifiqueService($agentMissionSpecifiqueService);
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
        $controller->setFormationElementService($formationElementService);
        $controller->setHasFormationCollectionService($hasFormationElementService);

        $controller->setParcoursDeFormationService($parcoursService);
        $controller->setCategorieService($categorieService);

        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setValidationTypeService($validationTypeService);
        $controller->setNatureService($natureService);
        $controller->setFichierService($fichierService);
        $controller->setFormationService($formationService);
        $controller->setApplicationService($applicationService);

        $controller->setStructureService($structureService);

        $controller->setApplicationElementForm($applicationElementForm);
        $controller->setCompetenceElementForm($competenceElementForm);
        $controller->setFormationElementForm($formationElementForm);
        $controller->setUploadForm($uploadForm);

        /**
         * @var AgentPPPService $agentPPPService
         * @var AgentPPPForm $agentPPPForm
         * @var AgentStageObservationService $agentStageObservationService
         * @var AgentStageObservationForm $agentStageObservationForm
         * @var AgentTutoratService $agentTutoratService
         * @var AgentTutoratForm $agentTutoratForm
         * @var AgentAccompagnementService $agentAccompagnementService
         * @var AgentAccompagnementForm $agentAccompagnementForm
         */
        $agentPPPService = $container->get(AgentPPPService::class);
        $agentPPPForm = $container->get('FormElementManager')->get(AgentPPPForm::class);
        $agentStageObservationService = $container->get(AgentStageObservationService::class);
        $agentStageObservationForm = $container->get('FormElementManager')->get(AgentStageObservationForm::class);
        $agentTutoratService = $container->get(AgentTutoratService::class);
        $agentTutoratForm = $container->get('FormElementManager')->get(AgentTutoratForm::class);
        $agentAccompagnementService = $container->get(AgentAccompagnementService::class);
        $agentAccompagnementForm = $container->get('FormElementManager')->get(AgentAccompagnementForm::class);

        $controller->setAgentPPPService($agentPPPService);
        $controller->setAgentPPPForm($agentPPPForm);
        $controller->setAgentStageObservationService($agentStageObservationService);
        $controller->setAgentStageObservationForm($agentStageObservationForm);
        $controller->setAgentTutoratService($agentTutoratService);
        $controller->setAgentTutoratForm($agentTutoratForm);
        $controller->setAgentAccompagnementService($agentAccompagnementService);
        $controller->setAgentAccompagnementForm($agentAccompagnementForm);

        /**
         * @var EtatService $etatService
         * @var EtatTypeService $etatTypeService
         */
        $etatService = $container->get(EtatService::class);
        $etatTypeService = $container->get(EtatTypeService::class);

        $controller->setEtatService($etatService);
        $controller->setEtatTypeService($etatTypeService);

        return $controller;
    }
}