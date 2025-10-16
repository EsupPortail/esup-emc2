<?php

namespace FicheMetier\Form\TendanceType;

use FicheMetier\Service\TendanceType\TendanceTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class TendanceTypeFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): TendanceTypeForm
    {
        /**
         * @var TendanceTypeService $tendanceTypeService
         * @var TendanceTypeHydrator $tendanceTypeHydrator
         */
        $tendanceTypeService = $container->get(TendanceTypeService::class);
        $tendanceTypeHydrator = $container->get('HydratorManager')->get(TendanceTypeHydrator::class);

        $form = new TendanceTypeForm();
        $form->setTendanceTypeService($tendanceTypeService);
        $form->setHydrator($tendanceTypeHydrator);
        return $form;
    }
}