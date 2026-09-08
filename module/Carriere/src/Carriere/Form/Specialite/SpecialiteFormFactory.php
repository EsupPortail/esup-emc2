<?php

namespace Carriere\Form\Specialite;

use Carriere\Service\Correspondance\CorrespondanceService;
use Carriere\Service\CorrespondanceType\CorrespondanceTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SpecialiteFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SpecialiteForm
    {
        /**
         * @var CorrespondanceService $correspondanceService
         * @var CorrespondanceTypeService $correspondanceTypeService
         * @var SpecialiteHydrator $hydrator
         */
        $correspondanceService = $container->get(CorrespondanceService::class);
        $correspondanceTypeService = $container->get(CorrespondanceTypeService::class);
        $hydrator = $container->get('HydratorManager')->get(SpecialiteHydrator::class);

        $form = new SpecialiteForm();
        $form->setCorrespondanceService($correspondanceService);
        $form->setCorrespondanceTypeService($correspondanceTypeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}
