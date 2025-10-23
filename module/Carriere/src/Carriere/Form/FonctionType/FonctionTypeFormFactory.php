<?php

namespace Carriere\Form\FonctionType;

use Carriere\Service\FonctionType\FonctionTypeService;
use Psr\Container\ContainerInterface;

class FonctionTypeFormFactory
{
    public function __invoke(ContainerInterface $container): FonctionTypeForm
    {
        /**
         * @var FonctionTypeService $fonctionTypeService
         * @var FonctionTypeHydrator $hydrator
         */
        $fonctionTypeService = $container->get(FonctionTypeService::class);
        $hydrator = $container->get('HydratorManager')->get(FonctionTypeHydrator::class);

        $form = new FonctionTypeForm();
        $form->setFonctionTypeService($fonctionTypeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}
