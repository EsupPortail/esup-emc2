<?php

namespace Formation\Controller;

use Formation\Service\FormationInstance\FormationInstanceService;
use Interop\Container\ContainerInterface;
use Mailing\Service\Mailing\MailingService;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenParametre\Service\Parametre\ParametreService;

class FormationConsoleControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return FormationConsoleController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EtatService $etatService
         * @var FormationInstanceService $formationInstanceService
         * @var MailingService $mailingService
         * @var ParametreService $parametreService
         */
        $etatService = $container->get(EtatService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $mailingService = $container->get(MailingService::class);
        $parametreService = $container->get(ParametreService::class);

        $controller = new FormationConsoleController();
        $controller->setEtatService($etatService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setMailingService($mailingService);
        $controller->setParametreService($parametreService);
        return $controller;
    }
}