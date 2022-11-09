<?php

namespace Formation\Controller;

use Formation\Form\Seance\SeanceForm;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\Seance\SeanceService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenDbImport\Entity\Db\Service\Source\SourceService;

class SeanceControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return SeanceController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SeanceController
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var SeanceService $seanceService
         * @var SourceService $sourceService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $seanceService = $container->get(SeanceService::class);
        $sourceService = $container->get(SourceService::class);

        /**
         * @var SeanceForm $seanceForm
         */
        $seanceForm = $container->get('FormElementManager')->get(SeanceForm::class);

        $controller = new SeanceController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setSeanceService($seanceService);
        $controller->setSourceService($sourceService);
        $controller->setSeanceForm($seanceForm);
        return $controller;
    }
}