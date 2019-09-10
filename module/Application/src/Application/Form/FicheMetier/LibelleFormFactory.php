<?php

namespace Application\Form\FicheMetier;

use Application\Service\RessourceRh\RessourceRhService;
use Interop\Container\ContainerInterface;

class LibelleFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var LibelleHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(LibelleHydrator::class);

        /** @var RessourceRhService $ressourceRhService */
        $ressourceRhService = $container->get(RessourceRhService::class);

        $form = new LibelleForm();
        $form->setRessourceRhService($ressourceRhService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}