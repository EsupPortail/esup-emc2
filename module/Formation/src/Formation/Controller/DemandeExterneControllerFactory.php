<?php

namespace Formation\Controller;

use Application\Form\SelectionAgent\SelectionAgentForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Fichier\Form\Upload\UploadForm;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Nature\NatureService;
use Formation\Form\Demande2Formation\Demande2FormationForm;
use Formation\Form\DemandeExterne\DemandeExterneForm;
use Formation\Form\Justification\JustificationForm;
use Formation\Form\SelectionGestionnaire\SelectionGestionnaireForm;
use Formation\Service\DemandeExterne\DemandeExterneService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\Notification\NotificationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;

class DemandeExterneControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return DemandeExterneController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): DemandeExterneController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var DemandeExterneService $demandeExterneService
         * @var EtatInstanceService $etatInstanceService
         * @var EtatTypeService $etatTypeService
         * @var FichierService $fichierService
         * @var FormationGroupeService $formationGroupeService
         * @var NatureService $natureService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var UserService $userService
         * @var ValidationInstanceService $validationInstanceService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $demandeExterneService = $container->get(DemandeExterneService::class);
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $fichierService = $container->get(FichierService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);
        $natureService = $container->get(NatureService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
        $userService = $container->get(UserService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);

        /**
         * @var DemandeExterneForm $demandeExterneForm
         * @var Demande2FormationForm $demande2formationForm
         * @var JustificationForm $justificationForm
         * @var SelectionAgentForm $selectionAgentForm
         * @var SelectionGestionnaireForm $selectionGestionnaireForm
         * @var UploadForm $uploadForm
         */
        $demandeExterneForm = $container->get('FormElementManager')->get(DemandeExterneForm::class);
        $demande2formationForm = $container->get('FormElementManager')->get(Demande2FormationForm::class);
        $justificationForm = $container->get('FormElementManager')->get(JustificationForm::class);
        $selectionAgentForm = $container->get('FormElementManager')->get(SelectionAgentForm::class);
        $selectionGestionnaireForm = $container->get('FormElementManager')->get(SelectionGestionnaireForm::class);
        $uploadForm = $container->get('FormElementManager')->get(UploadForm::class);


        $controller = new DemandeExterneController();
        $controller->setAgentService($agentService);
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setDemandeExterneService($demandeExterneService);
        $controller->setEtatInstanceService($etatInstanceService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setFichierService($fichierService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setNatureService($natureService);
        $controller->setNotificationService($notificationService);
        $controller->setParametreService($parametreService);
        $controller->setUserService($userService);
        $controller->setValidationInstanceService($validationInstanceService);

        $controller->setDemandeExterneForm($demandeExterneForm);
        $controller->setDemande2formationForm($demande2formationForm);
        $controller->setJustificationForm($justificationForm);
        $controller->setSelectionAgentForm($selectionAgentForm);
        $controller->setSelectionGestionnaireForm($selectionGestionnaireForm);
        $controller->setUploadForm($uploadForm);

        return $controller;
    }
}