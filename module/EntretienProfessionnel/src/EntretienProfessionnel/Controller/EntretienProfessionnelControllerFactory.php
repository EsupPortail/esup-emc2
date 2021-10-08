<?php

namespace EntretienProfessionnel\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\Configuration\ConfigurationService;
use Application\Service\ParcoursDeFormation\ParcoursDeFormationService;
use Application\Service\Structure\StructureService;
use Application\Service\Url\UrlService;
use Autoform\Service\Formulaire\FormulaireInstanceService;
use Autoform\Service\Formulaire\FormulaireService;
use EntretienProfessionnel\Form\Campagne\CampagneForm;
use EntretienProfessionnel\Form\EntretienProfessionnel\EntretienProfessionnelForm;
use EntretienProfessionnel\Form\Observation\ObservationForm;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Observation\ObservationService;
use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenRenderer\Service\Contenu\ContenuService;
use UnicaenUtilisateur\Service\User\UserService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;
use Zend\View\Renderer\PhpRenderer;

class EntretienProfessionnelControllerFactory {

    public function __invoke(ContainerInterface $container) : EntretienProfessionnelController
    {
        /**
         * @var AgentService $agentService
         * @var ContenuService $contenuService
         * @var UserService $userService
         * @var ConfigurationService $configurationService
         * @var EntretienProfessionnelService $entretienProfesionnelService
         * @var EtatService $etatService
         * @var EtatTypeService $etatTypeService
         * @var CampagneService $campagneService
         * @var ObservationService $observationService
         * @var FormulaireService $formulaireService
         * @var FormulaireInstanceService $formulaireInstanceService
         * @var MailingService $mailingService
         * @var ParcoursDeFormationService $parcoursDeFormationService
         * @var StructureService $structureService
         * @var ValidationInstanceService $validationInstanceService
         * @var ValidationTypeService $validationTypeService
         * @var UrlService $urlService
         */
        $agentService = $container->get(AgentService::class);
        $contenuService = $container->get(ContenuService::class);
        $userService = $container->get(UserService::class);
        $configurationService = $container->get(ConfigurationService::class);
        $etatService = $container->get(EtatService::class);
        $etatTypeService = $container->get(EtatTypeService::class);

        $entretienProfesionnelService = $container->get(EntretienProfessionnelService::class);
        $campagneService = $container->get(CampagneService::class);
        $observationService = $container->get(ObservationService::class);

        $formulaireService = $container->get(FormulaireService::class);
        $formulaireInstanceService = $container->get(FormulaireInstanceService::class);
        $mailingService = $container->get(MailingService::class);
        $parcoursDeFormationService = $container->get(ParcoursDeFormationService::class);
        $structureService = $container->get(StructureService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);
        $urlService = $container->get(UrlService::class);

        /**
         * @var EntretienProfessionnelForm $entretienProfessionnelForm
         * @var CampagneForm $campagneForm
         * @var ObservationForm $observationForm
         */
        $entretienProfessionnelForm = $container->get('FormElementManager')->get(EntretienProfessionnelForm::class);
        $campagneForm = $container->get('FormElementManager')->get(CampagneForm::class);
        $observationForm = $container->get('FormElementManager')->get(ObservationForm::class);

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var EntretienProfessionnelController $controller */
        $controller = new EntretienProfessionnelController();
        $controller->setRenderer($renderer);

        $controller->setAgentService($agentService);
        $controller->setContenuService($contenuService);
        $controller->setUserService($userService);
        $controller->setConfigurationService($configurationService);
        $controller->setEntretienProfessionnelService($entretienProfesionnelService);
        $controller->setEtatService($etatService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setCampagneService($campagneService);
        $controller->setObservationService($observationService);
        $controller->setFormulaireInstanceService($formulaireInstanceService);
        $controller->setParcoursDeFormationService($parcoursDeFormationService);
        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setValidationTypeService($validationTypeService);
        $controller->setFormulaireService($formulaireService);
        $controller->setMailingService($mailingService);
        $controller->setStructureService($structureService);
        $controller->setUrlService($urlService);

        $controller->setEntretienProfessionnelForm($entretienProfessionnelForm);
        $controller->setCampagneForm($campagneForm);
        $controller->setObservationForm($observationForm);

        return $controller;
    }
}
