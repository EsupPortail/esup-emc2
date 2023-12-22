<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Fichier\Form\Upload\UploadForm;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Nature\NatureService;
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
         * @var FichierService $fichierService
         * @var FormationInstanceService $formationInstanceService
         * @var InscriptionService $inscriptionService
         * @var NatureService $natureService
         * @var NotificationService $notificationService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $fichierService = $container->get(FichierService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $inscriptionService = $container->get(InscriptionService::class);
        $natureService = $container->get(NatureService::class);
        $notificationService = $container->get(NotificationService::class);

        $userService = $container->get(UserService::class);
        /**
         * @var InscriptionForm $inscriptionForm
         * @var InscriptionFraisService $inscriptionFraisService
         * @var InscriptionFraisForm $inscriptionFraisForm
         * @var JustificationForm $justificatifForm
         * @var UploadForm $uploadForm
         */
        $inscriptionForm = $container->get('FormElementManager')->get(InscriptionForm::class);
        $inscriptionFraisService = $container->get(InscriptionFraisService::class);
        $inscriptionFraisForm = $container->get('FormElementManager')->get(InscriptionFraisForm::class);
        $justificatifForm = $container->get('FormElementManager')->get(JustificationForm::class);
        $uploadForm = $container->get('FormElementManager')->get(UploadForm::class);

        $controller = new InscriptionController();
        $controller->setAgentService($agentService);
        $controller->setEtatInstanceService($etatInstanceService);
        $controller->setFichierService($fichierService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setInscriptionService($inscriptionService);
        $controller->setNatureService($natureService);
        $controller->setNotificationService($notificationService);
        $controller->setUserService($userService);

        $controller->setInscriptionForm($inscriptionForm);
        $controller->setInscriptionFraisService($inscriptionFraisService);
        $controller->setInscriptionFraisForm($inscriptionFraisForm);
        $controller->setJustificationForm($justificatifForm);
        $controller->setUploadForm($uploadForm);

        return $controller;
    }
}