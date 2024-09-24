<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentService;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use Fichier\Form\Upload\UploadForm;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Nature\NatureService;
use Formation\Form\Inscription\InscriptionForm;
use Formation\Form\InscriptionFrais\InscriptionFraisForm;
use Formation\Form\Justification\JustificationForm;
use Formation\Service\Inscription\InscriptionService;
use Formation\Service\InscriptionFrais\InscriptionFraisService;
use Formation\Service\Notification\NotificationService;
use Formation\Service\Session\SessionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEnquete\Service\Enquete\EnqueteService;
use UnicaenEnquete\Service\Instance\InstanceService;
use UnicaenEtat\Service\EtatInstance\EtatInstanceService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;

class InscriptionControllerFactory
{

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
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentSuperieurService $agentSuperieurService
         * @var EtatInstanceService $etatInstanceService
         * @var EnqueteService $enqueteService
         * @var FichierService $fichierService
         * @var InscriptionService $inscriptionService
         * @var InstanceService $enqueteService
         * @var NatureService $natureService
         * @var NotificationService $notificationService
         * @var ParametreService $parametreService
         * @var SessionService $sessionService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);
        $enqueteService = $container->get(EnqueteService::class);
        $etatInstanceService = $container->get(EtatInstanceService::class);
        $fichierService = $container->get(FichierService::class);
        $sessionService = $container->get(SessionService::class);
        $inscriptionService = $container->get(InscriptionService::class);
        $instanceService = $container->get(InstanceService::class);
        $natureService = $container->get(NatureService::class);
        $notificationService = $container->get(NotificationService::class);
        $parametreService = $container->get(ParametreService::class);
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
        $controller->setAgentAutoriteService($agentAutoriteService);
        $controller->setAgentSuperieurService($agentSuperieurService);
        $controller->setEnqueteService($enqueteService);
        $controller->setEtatInstanceService($etatInstanceService);
        $controller->setFichierService($fichierService);
        $controller->setInscriptionService($inscriptionService);
        $controller->setInstanceService($instanceService);
        $controller->setNatureService($natureService);
        $controller->setNotificationService($notificationService);
        $controller->setParametreService($parametreService);
        $controller->setSessionService($sessionService);
        $controller->setUserService($userService);

        $controller->setInscriptionForm($inscriptionForm);
        $controller->setInscriptionFraisService($inscriptionFraisService);
        $controller->setInscriptionFraisForm($inscriptionFraisForm);
        $controller->setJustificationForm($justificatifForm);
        $controller->setUploadForm($uploadForm);

        return $controller;
    }
}