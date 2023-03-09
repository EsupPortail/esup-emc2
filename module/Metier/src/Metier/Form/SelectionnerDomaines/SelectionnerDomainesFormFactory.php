<?php

namespace Metier\Form\SelectionnerDomaines;

use Metier\Service\Domaine\DomaineService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerDomainesFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SelectionnerDomainesForm
    {
        /**
         * @var DomaineService $domaineService
         * @var SelectionnerDomainesHydrator $hydrator
         */
        $domaineService = $container->get(DomaineService::class);
        $hydrator = $container->get('HydratorManager')->get(SelectionnerDomainesHydrator::class);

        $form = new SelectionnerDomainesForm();
        $form->setDomaineService($domaineService);
        $form->setHydrator($hydrator);
        return $form;
    }
}