<?php

namespace Application\Form\AjouterFormation;

use Application\Service\Formation\FormationService;
use Interop\Container\ContainerInterface;

class AjouterFormationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AjouterFormationForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var FormationService $formationService */
        $formationService = $container->get(FormationService::class);

        /** @var AjouterFormationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AjouterFormationHydrator::class);

        /** @var AjouterFormationForm $form */
        $form = new AjouterFormationForm();
        $form->setFormationService($formationService);
        $form->setHydrator($hydrator);
        return $form;
    }
}