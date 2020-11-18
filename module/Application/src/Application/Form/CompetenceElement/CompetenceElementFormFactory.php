<?php

namespace Application\Form\CompetenceElement;

use Application\Service\Competence\CompetenceService;
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
         */
        $competenceService = $container->get(CompetenceService::class);

        /** @var CompetenceElementHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceElementHydrator::class);

        /** @var CompetenceElementForm $form */
        $form = new CompetenceElementForm();
        $form->setCompetenceService($competenceService);
        $form->setHydrator($hydrator);
        return $form;
    }
}