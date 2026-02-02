<?php

namespace Element\Form\CompetenceElement;

use Element\Service\Competence\CompetenceService;
use Element\Service\NiveauMaitrise\NiveauMaitriseService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceElementFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceElementForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceElementForm
    {
        /**
         * @var CompetenceService $competenceService
         * @var NiveauMaitriseService $maitriseNiveauMaitriseService
         */
        $competenceService = $container->get(CompetenceService::class);
        $maitriseNiveauMaitriseService = $container->get(NiveauMaitriseService::class);

        /** @var CompetenceElementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceElementHydrator::class);

        $form = new CompetenceElementForm();
        $form->setCompetenceService($competenceService);
        $form->setNiveauMaitriseService($maitriseNiveauMaitriseService);
        $form->setHydrator($hydrator);
        return $form;
    }
}