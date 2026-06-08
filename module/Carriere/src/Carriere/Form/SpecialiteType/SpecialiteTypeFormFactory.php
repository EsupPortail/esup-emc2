<?php

namespace Carriere\Form\SpecialiteType;

use Carriere\Service\CorrespondanceType\CorrespondanceTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SpecialiteTypeFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SpecialiteTypeForm
    {
        /**
         * @var CorrespondanceTypeService $correspondanceTypeService
         * @var SpecialiteTypeHydrator $hydrator
         */
        $correspondanceTypeService = $container->get(CorrespondanceTypeService::class);
        $hydrator = $container->get('HydratorManager')->get(SpecialiteTypeHydrator::class);

        $form = new SpecialiteTypeForm();
        $form->setCorrespondanceTypeService($correspondanceTypeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}
