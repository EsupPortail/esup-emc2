<?php

namespace Application\Controller;

use Application\Form\EntretienProfessionnel\EntretienProfessionnelForm;
use Application\Form\EntretienProfessionnelCampagne\EntretienProfessionnelCampagneForm;
use Application\Service\Agent\AgentService;
use Application\Service\Configuration\ConfigurationService;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelCampagneService;
use Application\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Application\Service\Structure\StructureService;
use Autoform\Service\Formulaire\FormulaireInstanceService;
use Autoform\Service\Formulaire\FormulaireService;
use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;
use UnicaenUtilisateur\Service\User\UserService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;
use Zend\View\Renderer\PhpRenderer;

class EntretienProfessionnelControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var UserService $userService
         * @var ConfigurationService $configurationService
         * @var EntretienProfessionnelService $entretienProfesionnelService
         * @var EntretienProfessionnelCampagneService $entretienProfesionnelCampagneService
         * @var FormulaireService $formulaireService
         * @var FormulaireInstanceService $formulaireInstanceService
         * @var MailingService $mailingService
         * @var StructureService $structureService
         * @var ValidationInstanceService $validationInstanceService
         * @var ValidationTypeService $validationTypeService
         */
        $agentService = $container->get(AgentService::class);
        $userService = $container->get(UserService::class);
        $configurationService = $container->get(ConfigurationService::class);
        $entretienProfesionnelService = $container->get(EntretienProfessionnelService::class);
        $entretienProfesionnelCampagneService = $container->get(EntretienProfessionnelCampagneService::class);
        $formulaireService = $container->get(FormulaireService::class);
        $formulaireInstanceService = $container->get(FormulaireInstanceService::class);
        $mailingService = $container->get(MailingService::class);
        $structureService = $container->get(StructureService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);

        /**
         * @var EntretienProfessionnelForm $entretienProfessionnelForm
         * @var EntretienProfessionnelCampagneForm $campagneForm
         */
        $entretienProfessionnelForm = $container->get('FormElementManager')->get(EntretienProfessionnelForm::class);
        $campagneForm = $container->get('FormElementManager')->get(EntretienProfessionnelCampagneForm::class);

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        /** @var EntretienProfessionnelController $controller */
        $controller = new EntretienProfessionnelController();
        $controller->setRenderer($renderer);
        $controller->setAgentService($agentService);
        $controller->setUserService($userService);
        $controller->setConfigurationService($configurationService);
        $controller->setEntretienProfessionnelService($entretienProfesionnelService);
        $controller->setEntretienProfessionnelCampagneService($entretienProfesionnelCampagneService);
        $controller->setFormulaireInstanceService($formulaireInstanceService);
        $controller->setValidationInstanceService($validationInstanceService);
        $controller->setValidationTypeService($validationTypeService);
        $controller->setFormulaireService($formulaireService);
        $controller->setMailingService($mailingService);
        $controller->setStructureService($structureService);
        $controller->setEntretienProfessionnelForm($entretienProfessionnelForm);
        $controller->setEntretienProfessionnelCampagneForm($campagneForm);
        return $controller;
    }
}
