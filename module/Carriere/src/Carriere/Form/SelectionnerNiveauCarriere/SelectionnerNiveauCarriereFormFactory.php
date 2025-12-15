<?php

namespace Carriere\Form\SelectionnerNiveauCarriere;

use Carriere\Service\Niveau\NiveauService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerNiveauCarriereFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionnerNiveauCarriereForm
    {
        /**
         * @var NiveauService $niveauService
         * @var SelectionnerNiveauCarriereHydrator $hydrator
         */
        $niveauService = $container->get(NiveauService::class);
        $hydrator = $container->get('HydratorManager')->get(SelectionnerNiveauCarriereHydrator::class);

        $form = new SelectionnerNiveauCarriereForm();
        $form->setNiveauService($niveauService);
        $form->setHydrator($hydrator);
        return $form;

    }

}
