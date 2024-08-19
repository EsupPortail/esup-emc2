<?php

namespace FicheReferentiel\Controller;

use Application\Service\Macro\MacroService;
use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Metier\Service\Metier\MetierService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ReferensImporterControllerFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ReferensImporterController
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceReferentielService $competenceRefrentielService
         * @var MetierService $metierService
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceRefrentielService = $container->get(CompetenceReferentielService::class);
        $metierService = $container->get(MetierService::class);

        $controller = new ReferensImporterController();
        $controller->setCompetenceService($competenceService);
        $controller->setCompetenceReferentielService($competenceRefrentielService);
        $controller->setMetierService($metierService);
        return $controller;
    }
}