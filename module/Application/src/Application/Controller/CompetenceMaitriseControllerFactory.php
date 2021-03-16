<?php

namespace Application\Controller;

use Application\Form\CompetenceMaitrise\CompetenceMaitriseForm;
use Application\Service\CompetenceMaitrise\CompetenceMaitriseService;
use Interop\Container\ContainerInterface;

class CompetenceMaitriseControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceMaitriseController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CompetenceMaitriseService $competenceMaitriseService
         * @var CompetenceMaitriseForm $competenceMaitriseForm
         */
        $competenceMaitriseService = $container->get(CompetenceMaitriseService::class);
        $competenceMaitriseForm = $container->get('FormElementManager')->get(CompetenceMaitriseForm::class);

        $controller = new CompetenceMaitriseController();
        $controller->setCompetenceMaitriseService($competenceMaitriseService);
        $controller->setCompetenceMaitriseForm($competenceMaitriseForm);
        return $controller;
    }
}