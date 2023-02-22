<?php

namespace Metier\Form\SelectionnerMetier;

use Metier\Service\Metier\MetierService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerMetierFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionnerMetierForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SelectionnerMetierForm
    {
        /** @var SelectionnerMetierHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(SelectionnerMetierHydrator::class);

        /**
         * @var MetierService $metierService
         */
        $metierService = $container->get(MetierService::class);

        $form = new SelectionnerMetierForm();
        $form->setMetierService($metierService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}