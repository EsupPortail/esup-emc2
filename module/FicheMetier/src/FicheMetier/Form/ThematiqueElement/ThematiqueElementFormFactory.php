<?php

namespace FicheMetier\Form\ThematiqueElement;

use Element\Service\NiveauMaitrise\NiveauMaitriseService;
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
         * @var NiveauMaitriseService $niveauService
         * @var ThematiqueElementHydrator $thematiqueElementHydrator
         */
        $niveauService = $container->get(NiveauMaitriseService::class);
        $thematiqueElementHydrator = $container->get('HydratorManager')->get(ThematiqueElementHydrator::class);

        $form = new ThematiqueElementForm();
        $form->setNiveauMaitriseService($niveauService);
        $form->setHydrator($thematiqueElementHydrator);
        return $form;
    }

}