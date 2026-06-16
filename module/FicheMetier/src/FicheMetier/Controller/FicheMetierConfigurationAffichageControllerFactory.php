<?php

namespace FicheMetier\Controller;

use FicheMetier\Provider\Parametre\FicheMetierParametres;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class FicheMetierConfigurationAffichageControllerFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FicheMetierConfigurationAffichageController
    {
        /**
         * @var ParametreService $parametreService
         */
        $parametreService = $container->get(ParametreService::class);

        $controller = new FicheMetierConfigurationAffichageController();
        $controller->setParametreService($parametreService);
        return $controller;
    }
}
