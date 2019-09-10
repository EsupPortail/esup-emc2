<?php

namespace Application\Form\RessourceRh;

use Application\Service\Domaine\DomaineService;
use Interop\Container\ContainerInterface;

class MetierFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var MetierHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MetierHydrator::class);

        /** @var DomaineService $domaineService */
        $domaineService = $container->get(DomaineService::class);

        $form = new MetierForm();
        $form->setDomaineService($domaineService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}