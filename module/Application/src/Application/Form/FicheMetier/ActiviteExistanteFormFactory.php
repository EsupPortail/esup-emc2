<?php

namespace Application\Form\FicheMetier;

use Application\Service\Activite\ActiviteService;
use Interop\Container\ContainerInterface;

class ActiviteExistanteFormFactory{

    public function __invoke(ContainerInterface $container)
    {
        /** @var ActiviteExistanteHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ActiviteExistanteHydrator::class);

        /** @var ActiviteService $activiteService */
        $activiteService = $container->get(ActiviteService::class);

        $form = new ActiviteExistanteForm();
        $form->setActiviteService($activiteService);
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}