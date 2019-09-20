<?php

namespace Application\Form\Competence;

use Application\Service\Competence\CompetenceService;
use Interop\Container\ContainerInterface;

class CompetenceFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var CompetenceService $competenceService */
        $competenceService = $container->get(CompetenceService::class);
        /** @var CompetenceHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceHydrator::class);

        /** @var CompetenceForm $form */
        $form = new CompetenceForm();
        $form->setCompetenceService($competenceService);
        $form->setHydrator($hydrator);
        return $form;
    }
}