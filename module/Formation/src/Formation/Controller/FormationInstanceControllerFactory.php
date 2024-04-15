<?php

namespace Formation\Controller;

use Formation\Form\FormationInstance\FormationInstanceForm;
use Formation\Form\SelectionFormateur\SelectionFormateurForm;
use Formation\Form\SelectionGestionnaire\SelectionGestionnaireForm;
use Formation\Service\Formateur\FormateurService;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\Notification\NotificationService;
use Formation\Service\Presence\PresenceService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatCategorie\EtatCategorieService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenMail\Service\Mail\MailService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;

class FormationInstanceControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationInstanceController
    {
        /**
         * @var EtatCategorieService $etatCategorieService
         * @var EtatTypeService $etatTypeService
         * @var FormateurService $formateurService
         * @var FormationService $formationService
         * @var FormationInstanceService $formationInstanceService
         * @var MailService $mailService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var PresenceService $presenceService
         * @var UserService $userService
         */
        $etatCategorieService = $container->get(EtatCategorieService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $formateurService = $container->get(FormateurService::class);
        $formationService = $container->get(FormationService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $mailService = $container->get(MailService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
        $presenceService = $container->get(PresenceService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var FormationInstanceForm $formationInstanceForm
         * @var SelectionFormateurForm $selectionFormateurForm
         * @var SelectionGestionnaireForm $selectionGestionnaireForm
         */
        $formationInstanceForm = $container->get('FormElementManager')->get(FormationInstanceForm::class);
        $selectionFormateurForm = $container->get('FormElementManager')->get(SelectionFormateurForm::class);
        $selectionGestionnaireForm = $container->get('FormElementManager')->get(SelectionGestionnaireForm::class);

        $controller = new FormationInstanceController();
        $controller->setEtatCategorieService($etatCategorieService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setFormateurService($formateurService);
        $controller->setFormationService($formationService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setMailService($mailService);
        $controller->setNotificationService($notificationService);
        $controller->setParametreService($parametreService);
        $controller->setPresenceService($presenceService);
        $controller->setUserService($userService);
        $controller->setFormationInstanceForm($formationInstanceForm);
        $controller->setSelectionFormateurForm($selectionFormateurForm);
        $controller->setSelectionGestionnaireForm($selectionGestionnaireForm);

        return $controller;
    }

}