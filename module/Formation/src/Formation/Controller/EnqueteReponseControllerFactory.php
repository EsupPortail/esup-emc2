<?php

namespace Formation\Controller;

use Formation\Service\EnqueteCategorie\EnqueteCategorieService;
use Formation\Service\EnqueteReponse\EnqueteReponseService;
use Formation\Service\Formateur\FormateurService;
use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class EnqueteReponseControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return EnqueteReponseController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EnqueteReponseController
    {
        /**
         * @var EnqueteCategorieService $enqueteCategorieService
         * @var EnqueteReponseService $enqueteReponseService
         * @var FormationService $formationService
         * @var FormateurService $formateurService
         */
        $enqueteCategorieService = $container->get(EnqueteCategorieService::class);
        $enqueteReponseService = $container->get(EnqueteReponseService::class);
        $formationService = $container->get(FormationService::class);
        $formateurService = $container->get(FormateurService::class);

        $controller = new EnqueteReponseController();
        $controller->setEnqueteCategorieService($enqueteCategorieService);
        $controller->setEnqueteReponseService($enqueteReponseService);
        $controller->setFormationService($formationService);
        $controller->setFormateurService($formateurService);

        return $controller;
    }
}