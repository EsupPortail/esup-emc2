<?php

namespace Application\Form\FicheMetier;

use Application\Service\Competence\CompetenceService;
use Interop\Container\ContainerInterface;

class GererCompetenceFormFactory {

    /**
     * @param ContainerInterface $container
     * @return GererCompetenceForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var CompetenceService $competenceService */
        $competenceService = $container->get(CompetenceService::class);
        /** @var GererCompetenceHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(GererCompetenceHydrator::class);

        /** @var GererCompetenceForm $form */
        $form = new GererCompetenceForm();
        $form->setCompetenceService($competenceService);
        $form->setHydrator($hydrator);
        return $form;
    }
}