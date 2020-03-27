<?php

namespace Application\Controller;

use Application\Service\Agent\AgentService;
use Fichier\Form\Upload\UploadForm;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Nature\NatureService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\User\UserService;;

class AgentFichierControllerFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var FichierService $fichierService
         * @var NatureService $natureService
         */
        $agentService = $container->get(AgentService::class);
        $fichierService = $container->get(FichierService::class);
        $natureService = $container->get(NatureService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var UploadForm $uploadForm
         */
        $uploadForm = $container->get('FormElementManager')->get(UploadForm::class);

        /** @var AgentFichierController $controller */
        $controller = new AgentFichierController();
        $controller->setAgentService($agentService);
        $controller->setFichierService($fichierService);
        $controller->setNatureService($natureService);
        $controller->setUserService($userService);
        $controller->setUploadForm($uploadForm);
        return $controller;
    }
}