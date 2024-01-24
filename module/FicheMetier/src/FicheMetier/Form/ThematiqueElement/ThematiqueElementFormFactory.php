<?php

namespace FicheMetier\Form\ThematiqueElement;

use Element\Service\Niveau\NiveauService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ThematiqueElementFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ThematiqueElementForm
    {
        /**
         * @var NiveauService $niveauService
         * @var ThematiqueElementHydrator $thematiqueElementHydrator
         */
        $niveauService = $container->get(NiveauService::class);
        $thematiqueElementHydrator = $container->get('HydratorManager')->get(ThematiqueElementHydrator::class);

        $form = new ThematiqueElementForm();
        $form->setNiveauService($niveauService);
        $form->setHydrator($thematiqueElementHydrator);
        return $form;
    }

}