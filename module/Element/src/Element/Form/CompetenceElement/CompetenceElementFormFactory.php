<?php

namespace Element\Form\CompetenceElement;

use Element\Service\Competence\CompetenceService;
use Element\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class CompetenceElementFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceElementForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CompetenceService $competenceService
         * @var NiveauService $maitriseNiveauService
         */
        $competenceService = $container->get(CompetenceService::class);
        $maitriseNiveauService = $container->get(NiveauService::class);

        /** @var CompetenceElementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceElementHydrator::class);

        /** @var CompetenceElementForm $form */
        $form = new CompetenceElementForm();
        $form->setCompetenceService($competenceService);
        $form->setNiveauService($maitriseNiveauService);
        $form->setHydrator($hydrator);
        return $form;
    }
}