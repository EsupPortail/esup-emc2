<?php

namespace Carriere\Form\NiveauFonction;

use Carriere\Service\NiveauFonction\NiveauFonctionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class NiveauFonctionFormFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): NiveauFonctionForm
    {
        /**
         * @var NiveauFonctionService $niveauFonctionService
         * @var NiveauFonctionHydrator $hydrator
         */
        $niveauFonctionService = $container->get(NiveauFonctionService::class);
        $hydrator = $container->get('HydratorManager')->get(NiveauFonctionHydrator::class);

        $form = new NiveauFonctionForm();
        $form->setNiveauFonctionService($niveauFonctionService);
        $form->setHydrator($hydrator);
        return $form;
    }
}
