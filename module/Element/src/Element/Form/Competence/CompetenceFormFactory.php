<?php

namespace Element\Form\Competence;

use Element\Service\Competence\CompetenceService;
use Element\Service\CompetenceDiscipline\CompetenceDisciplineService;
use Element\Service\CompetenceReferentiel\CompetenceReferentielService;
use Element\Service\CompetenceTheme\CompetenceThemeService;
use Element\Service\CompetenceType\CompetenceTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\Referentiel\ReferentielService;

class CompetenceFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return CompetenceForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CompetenceForm
    {
        /**
         * @var CompetenceService $competenceService
         * @var CompetenceDisciplineService $competenceDisciplineService
         * @var CompetenceThemeService $competenceThemeService
         * @var CompetenceTypeService $competenceTypeService
         * @var ReferentielService $referentielService
         */
        $competenceService = $container->get(CompetenceService::class);
        $competenceDisciplineService = $container->get(CompetenceDisciplineService::class);
        $referentielService = $container->get(ReferentielService::class);
        $competenceThemeService = $container->get(CompetenceThemeService::class);
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        /** @var CompetenceHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceHydrator::class);

        $form = new CompetenceForm();
        $form->setCompetenceService($competenceService);
        $form->setReferentielService($referentielService);
        $form->setCompetenceDisciplineService($competenceDisciplineService);
        $form->setCompetenceThemeService($competenceThemeService);
        $form->setCompetenceTypeService($competenceTypeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}