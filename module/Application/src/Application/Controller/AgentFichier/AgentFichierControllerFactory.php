<?php

namespace Application\Controller\AgentFichier;

use Application\Service\Agent\AgentService;
use Fichier\Form\Upload\UploadForm;
use Fichier\Service\Fichier\FichierService;
use Fichier\Service\Nature\NatureService;
use Utilisateur\Service\User\UserService;
use Zend\Mvc\Controller\ControllerManager;

class AgentFichierControllerFactory {

    public function __invoke(ControllerManager $manager)
    {
        /**
         * @var AgentService $agentService
         * @var FichierService $fichierService
         * @var NatureService $natureService
         */
        $agentService = $manager->getServiceLocator()->get(AgentService::class);
        $fichierService = $manager->getServiceLocator()->get(FichierService::class);
        $natureService = $manager->getServiceLocator()->get(NatureService::class);
        $userService = $manager->getServiceLocator()->get(UserService::class);

        /**
         * @var UploadForm $uploadForm
         */
        $uploadForm = $manager->getServiceLocator()->get('FormElementManager')->get(UploadForm::class);

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