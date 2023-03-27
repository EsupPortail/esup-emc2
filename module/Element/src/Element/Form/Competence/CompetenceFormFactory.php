<?php

namespace Element\Form\Competence;

use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceTheme\CompetenceThemeService;
use Element\Service\CompetenceType\CompetenceTypeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceForm
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceThemeService $competenceThemeService
         * @var CompetenceTypeService $competenceTypeService
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        /** @var CompetenceHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceHydrator::class);

        $form = new CompetenceForm();
        $form->setCompetenceService($competenceService);
        $form->setCompetenceThemeService($competenceThemeService);
        $form->setCompetenceTypeService($competenceTypeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}