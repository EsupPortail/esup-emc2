<?php

namespace Carriere\Form\NiveauEnveloppe;

use Carriere\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NiveauEnveloppeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauEnveloppeForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : NiveauEnveloppeForm
    {
        /** @var NiveauService $niveauService */
        $niveauService = $container->get(NiveauService::class);
        /** @var NiveauEnveloppeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(NiveauEnveloppeHydrator::class);

        $form = new NiveauEnveloppeForm();
        $form->setNiveauService($niveauService);
        $form->setHydrator($hydrator);

        $array = [];
        $niveaux = $niveauService->getNiveaux();
        foreach ($niveaux as $niveau) $array[$niveau->getId()] = $niveau;

        $form->niveaux = $array;

        return $form;
    }
}