<?php

namespace FicheMetier\Service\Repertoire;

use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class RepertoireServiceFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): RepertoireService
    {
        $service = new RepertoireService();
        return $service;
    }
}