<?php

namespace Application\Form\Application;

use Application\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;
use Zend\Form\FormElementManager;

class ApplicationFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationService $formationService */
        $formationService = $container->get(FormationService::class);

        /** @var ApplicationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ApplicationHydrator::class);

        $form = new ApplicationForm();
        $form->setFormationService($formationService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}