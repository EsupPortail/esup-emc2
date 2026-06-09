<?php

namespace Agent\Controller;

use Agent\Service\Agent\AgentService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenFichier\Form\Upload\UploadForm;
use UnicaenFichier\Service\Fichier\FichierService;
use UnicaenFichier\Service\Nature\NatureService;
use UnicaenParametre\Service\Parametre\ParametreService;

class PortfolioControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : PortfolioControler
    {
        /**
         * @var AgentService $agentService
         * @var FichierService $ficherService
         * @var NatureService $natureService
         * @var ParametreService $parametreService
         */
        $agentService = $container->get(AgentService::class);
        $fichierService = $container->get(FichierService::class);
        $natureService = $container->get(NatureService::class);
        $parametreService = $container->get(ParametreService::class);

        /**
         * @var UploadForm $uploadForm
         */
        $uploadForm = $container->get('FormElementManager')->get(UploadForm::class);

        $controller = new PortfolioControler();
        $controller->setAgentService($agentService);
        $controller->setFichierService($fichierService);
        $controller->setNatureService($natureService);
        $controller->setParametreService($parametreService);
        $controller->setUploadForm($uploadForm);
        return $controller;
    }
}
