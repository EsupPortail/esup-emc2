<?php

namespace EntretienProfessionnel\Controller;

use Application\Form\SelectionAgent\SelectionAgentForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Application\Service\Macro\MacroService;
use EntretienProfessionnel\Form\Campagne\CampagneForm;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementAutoriteService;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementSuperieurService;
use EntretienProfessionnel\Service\Notification\NotificationService;
use EntretienProfessionnel\Service\Url\UrlService;
use EntretienProfessionnel\Service\Url\UrlServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use Structure\Service\StructureAgentForce\StructureAgentForceService;
use UnicaenIndicateur\Service\Categorie\CategorieService;
use UnicaenIndicateur\Service\Indicateur\IndicateurService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenUtilisateur\Service\User\UserService;

class CampagneControllerFactory extends AbstractActionController
{
    /**
     * @param ContainerInterface $container
     * @return CampagneController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var CampagneService $campagneService
         * @var CategorieService $categorieService
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var IndicateurService $indicateurService
         * @var MacroService $macroService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var RappelCampagneAvancementAutoriteService $rappelCampagneAvancementAutoriteService
         * @var RappelCampagneAvancementSuperieurService $rappelCampagneAvancementSuperieurService
         * @var RenduService $renduService ;
         * @var StructureService $structureService
         * @var StructureAgentForceService $structureAgentForceService
         * @var UrlServiceAwareTrait $urlService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $campagneService = $container->get(CampagneService::class);
        $categorieService = $container->get(CategorieService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $indicateurService = $container->get(IndicateurService::class);
        $macroService = $container->get(MacroService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
        $rappelCampagneAvancementAutoriteService = $container->get(RappelCampagneAvancementAutoriteService::class);
        $rappelCampagneAvancementSuperieurService = $container->get(RappelCampagneAvancementSuperieurService::class);
        $renduService = $container->get(RenduService::class);
        $structureService = $container->get(StructureService::class);
        $structureAgentForceService = $container->get(StructureAgentForceService::class);
        $urlService = $container->get(UrlService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var CampagneForm $campagneForm
         * @var SelectionAgentForm $selectionAgentForm
         */
        $campagneForm = $container->get('FormElementManager')->get(CampagneForm::class);
        $selectionAgentForm = $container->get('FormElementManager')->get(SelectionAgentForm::class);

        $controller = new CampagneController();
        $controller->setAgentService($agentService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setCampagneService($campagneService);
        $controller->setCategorieService($categorieService);
        $controller->setEntretienProfessionnelService($entretienProfessionnelService);
        $controller->setIndicateurService($indicateurService);
        $controller->setMacroService($macroService);
        $controller->setNotificationService($notificationService);
        $controller->setParametreService($parametreService);
        $controller->setRappelCampagneAvancementAutoriteService($rappelCampagneAvancementAutoriteService);
        $controller->setRappelCampagneAvancementSuperieurService($rappelCampagneAvancementSuperieurService);
        $controller->setRenduService($renduService);
        $controller->setStructureService($structureService);
        $controller->setStructureAgentForceService($structureAgentForceService);
        $controller->setUrlService($urlService);
        $controller->setUserService($userService);
        $controller->setCampagneForm($campagneForm);
        $controller->setSelectionAgentForm($selectionAgentForm);
        return $controller;
    }
}