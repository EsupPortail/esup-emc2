<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Formation\Form\Inscription\InscriptionForm;
use Formation\Form\InscriptionFrais\InscriptionFraisForm;
use Formation\Form\Justification\JustificationForm;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\Inscription\InscriptionService;
use Formation\Service\InscriptionFrais\InscriptionFraisService;
use Formation\Service\Notification\NotificationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;
use UnicaenUtilisateur\Service\User\UserService;

class InscriptionControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return InscriptionController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): InscriptionController
    {
        /**
         * @var AgentService $agentService
         * @var EtatInstanceService $etatInstanceService
         * @var FormationInstanceService $formationInstanceService
         * @var InscriptionService $inscriptionService
         * @var NotificationService $notificationService
         * @var UserService $userService
         * @var InscriptionForm $inscriptionForm
         */
        $agentService = $container->get(AgentService::class);
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $inscriptionService = $container->get(InscriptionService::class);
        $notificationService = $container->get(NotificationService::class);
        $inscriptionForm = $container->get('FormElementManager')->get(InscriptionForm::class);
        $userService = $container->get(UserService::class);
        /**
         * @var InscriptionFraisService $inscriptionFraisService
         * @var InscriptionFraisForm $inscriptionFraisForm
         */
        $inscriptionFraisService = $container->get(InscriptionFraisService::class);
        $inscriptionFraisForm = $container->get('FormElementManager')->get(InscriptionFraisForm::class);
        $justificatifForm = $container->get('FormElementManager')->get(JustificationForm::class);

        $controller = new InscriptionController();
        $controller->setAgentService($agentService);
        $controller->setEtatInstanceService($etatInstanceService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setInscriptionService($inscriptionService);
        $controller->setNotificationService($notificationService);
        $controller->setInscriptionForm($inscriptionForm);
        $controller->setInscriptionForm($inscriptionForm);
        $controller->setUserService($userService);

        $controller->setInscriptionFraisService($inscriptionFraisService);
        $controller->setInscriptionFraisForm($inscriptionFraisForm);
        $controller->setJustificationForm($justificatifForm);

        return $controller;
    }
}