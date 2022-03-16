<?php

namespace Application\Form\CompetenceElement;

use Application\Service\Competence\CompetenceService;
use Application\Service\MaitriseNiveau\MaitriseNiveauService;
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
         * @var MaitriseNiveauService $maitriseNiveauService
         */
        $competenceService = $container->get(CompetenceService::class);
        $maitriseNiveauService = $container->get(MaitriseNiveauService::class);

        /** @var CompetenceElementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceElementHydrator::class);

        /** @var CompetenceElementForm $form */
        $form = new CompetenceElementForm();
        $form->setCompetenceService($competenceService);
        $form->setMaitriseNiveauService($maitriseNiveauService);
        $form->setHydrator($hydrator);
        return $form;
    }
}