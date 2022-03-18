<?php

namespace Application\Form\Activite;

use Element\Service\Application\ApplicationService;
use Element\Service\Competence\CompetenceService;
use Formation\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class ActiviteFormFactory {

    public function __invoke(ContainerInterface $container) : ActiviteForm
    {
        /**
         * @var ApplicationService $applicationService
         * @var CompetenceService $competenceService
         * @var FormationService $formationService
         * @var ActiviteHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(ActiviteHydrator::class);
        $applicationService = $container->get(ApplicationService::class);
        $competenceService = $container->get(CompetenceService::class);
        $formationService = $container->get(FormationService::class);

        $form = new ActiviteForm();
        $form->setApplicationService($applicationService);
        $form->setCompetenceService($competenceService);
        $form->setFormationService($formationService);
        $form->setHydrator($hydrator);

        return $form;
    }
}