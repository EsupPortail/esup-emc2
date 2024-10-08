<?php

namespace Formation\Controller;

use Formation\Form\SelectionFormateur\SelectionFormateurForm;
use Formation\Form\SelectionGestionnaire\SelectionGestionnaireForm;
use Formation\Form\Session\SessionForm;
use Formation\Service\Formateur\FormateurService;
use Formation\Service\Formation\FormationService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Formation\Service\Inscription\InscriptionService;
use Formation\Service\InscriptionFrais\InscriptionFraisService;
use Formation\Service\Notification\NotificationService;
use Formation\Service\Presence\PresenceService;
use Formation\Service\Session\SessionService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEnquete\Service\Enquete\EnqueteService;
use UnicaenEnquete\Service\Resultat\ResultatService;
use UnicaenEtat\Service\EtatCategorie\EtatCategorieService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenMail\Service\Mail\MailService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;

class SessionControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SessionController
    {
        /**
         * @var EnqueteService $enqueteService
         * @var EtatCategorieService $etatCategorieService
         * @var EtatTypeService $etatTypeService
         * @var FormateurService $formateurService
         * @var FormationService $formationService
         * @var FormationGroupeService $formationGroupeService
         * @var InscriptionService $inscriptionService
         * @var InscriptionFraisService $inscriptionFraisService
         * @var MailService $mailService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var PresenceService $presenceService
         * @var ResultatService $resultatService
         * @var UserService $userService
         */
        $enqueteService = $container->get(EnqueteService::class);
        $etatCategorieService = $container->get(EtatCategorieService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $formateurService = $container->get(FormateurService::class);
        $formationService = $container->get(FormationService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);
        $inscriptionService = $container->get(InscriptionService::class);
        $inscriptionFraisService = $container->get(InscriptionFraisService::class);
        $mailService = $container->get(MailService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
        $presenceService = $container->get(PresenceService::class);
        $resultatService = $container->get(ResultatService::class);
        $sessionService = $container->get(SessionService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var SelectionFormateurForm $selectionFormateurForm
         * @var SelectionGestionnaireForm $selectionGestionnaireForm
         * @var SessionForm $sessionForm
         */
        $sessionForm = $container->get('FormElementManager')->get(SessionForm::class);
        $selectionFormateurForm = $container->get('FormElementManager')->get(SelectionFormateurForm::class);
        $selectionGestionnaireForm = $container->get('FormElementManager')->get(SelectionGestionnaireForm::class);

        $controller = new SessionController();
        $controller->setEnqueteService($enqueteService);
        $controller->setEtatCategorieService($etatCategorieService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setFormateurService($formateurService);
        $controller->setFormationService($formationService);
        $controller->setFormationGroupeService($formationGroupeService);
        $controller->setInscriptionService($inscriptionService);
        $controller->setInscriptionFraisService($inscriptionFraisService);
        $controller->setMailService($mailService);
        $controller->setNotificationService($notificationService);
        $controller->setParametreService($parametreService);
        $controller->setPresenceService($presenceService);
        $controller->setResultatService($resultatService);
        $controller->setSessionService($sessionService);
        $controller->setUserService($userService);
        $controller->setSessionForm($sessionForm);
        $controller->setSelectionFormateurForm($selectionFormateurForm);
        $controller->setSelectionGestionnaireForm($selectionGestionnaireForm);

        return $controller;
    }

}