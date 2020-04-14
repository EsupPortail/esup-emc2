<?php

namespace Application\Form\FicheMetier;

use Application\Service\Metier\MetierService;
use Application\Service\RessourceRh\RessourceRhService;
use Interop\Container\ContainerInterface;

class LibelleFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var LibelleHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(LibelleHydrator::class);

        /**
         * @var MetierService $metierService
         */
        $metierService = $container->get(MetierService::class);

        $form = new LibelleForm();
        $form->setMetierService($metierService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}