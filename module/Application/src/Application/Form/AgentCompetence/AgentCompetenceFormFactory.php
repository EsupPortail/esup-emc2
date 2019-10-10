<?php

namespace Application\Form\AgentCompetence;

use Application\Service\Competence\CompetenceService;
use Interop\Container\ContainerInterface;

class AgentCompetenceFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var CompetenceService $competenceService
         * @var AgentCompetenceHydrator $hydrator
         */
        $competenceService = $container->get(CompetenceService::class);
        $hydrator = $container->get('HydratorManager')->get(AgentCompetenceHydrator::class);

        /** @var AgentCompetenceForm $form */
        $form = new AgentCompetenceForm();
        $form->setCompetenceService($competenceService);
        $form->setHydrator($hydrator);
        return $form;
    }
}