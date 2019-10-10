<?php

namespace Application\Form\Activite;

use Application\Service\Application\ApplicationService;
use Application\Service\Competence\CompetenceService;
use Application\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;
use Zend\Form\FormElementManager;

class ActiviteFormFactory {

    public function __invoke(ContainerInterface $container)
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