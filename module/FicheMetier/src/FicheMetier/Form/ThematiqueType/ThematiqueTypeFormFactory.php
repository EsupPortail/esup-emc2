<?php

namespace FicheMetier\Form\ThematiqueType;

use FicheMetier\Service\ThematiqueType\ThematiqueTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ThematiqueTypeFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ThematiqueTypeForm
    {
        /**
         * @var ThematiqueTypeService $thematiqueTypeService
         * @var ThematiqueTypeHydrator $thematiqueTypeHydrator
         */
        $thematiqueTypeService = $container->get(ThematiqueTypeService::class);
        $thematiqueTypeHydrator = $container->get('HydratorManager')->get(ThematiqueTypeHydrator::class);

        $form = new ThematiqueTypeForm();
        $form->setThematiqueTypeService($thematiqueTypeService);
        $form->setHydrator($thematiqueTypeHydrator);
        return $form;
    }
}