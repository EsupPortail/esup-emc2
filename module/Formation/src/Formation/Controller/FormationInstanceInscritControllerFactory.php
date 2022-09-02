<?php

namespace Formation\Controller;

use Application\Form\SelectionAgent\SelectionAgentForm;
use Application\Service\Agent\AgentService;
use Formation\Form\Inscription\InscriptionForm;
use Formation\Service\DemandeExterne\DemandeExterneService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Formation\Service\Notification\NotificationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenMail\Service\Mail\MailService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenUtilisateur\Service\User\UserService;

class FormationInstanceInscritControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceInscritController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FormationInstanceInscritController
    {
        /**
         * @var AgentService $agentService
         * @var DemandeExterneService $demandeExterneService
         * @var EtatService $etatService
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var MailService $mailService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var RenduService $renduService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $demandeExterneService = $container->get(DemandeExterneService::class);
        $etatService = $container->get(EtatService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $mailService = $container->get(MailService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
        $renduService = $container->get(RenduService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var InscriptionForm $inscriptionForm
         * @var SelectionAgentForm $selectionAgentForm
         */
        $inscriptionForm = $container->get('FormElementManager')->get(InscriptionForm::class);
        $selectionAgentForm = $container->get('FormElementManager')->get(SelectionAgentForm::class);

        $controller = new FormationInstanceInscritController();
        $controller->setAgentService($agentService);
        $controller->setDemandeExterneService($demandeExterneService);
        $controller->setEtatService($etatService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setMailService($mailService);
        $controller->setNotificationService($notificationService);
        $controller->setParametreService($parametreService);
        $controller->setRenduService($renduService);
        $controller->setUserService($userService);
        $controller->setInscriptionForm($inscriptionForm);
        $controller->setSelectionAgentForm($selectionAgentForm);
        return $controller;
    }
}