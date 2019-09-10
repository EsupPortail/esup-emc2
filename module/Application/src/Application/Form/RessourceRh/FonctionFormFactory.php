<?php

namespace Application\Form\RessourceRh;

use Application\Service\Domaine\DomaineService;
use Interop\Container\ContainerInterface;

class FonctionFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var DomaineService $domaineService
         */
        $domaineService = $container->get(DomaineService::class);

        /** @var FonctionHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FonctionHydrator::class);

        /** @var FonctionForm $form */
        $form = new FonctionForm();
        $form->setDomaineService($domaineService);
        $form->init();
        $form->setHydrator($hydrator);
        return $form;
    }
}