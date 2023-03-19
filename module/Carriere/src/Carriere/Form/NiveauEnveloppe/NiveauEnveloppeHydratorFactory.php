<?php

namespace Carriere\Form\NiveauEnveloppe;

use Carriere\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NiveauEnveloppeHydratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return NiveauEnveloppeHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : NiveauEnveloppeHydrator
    {
        /**
         * @var NiveauService $metierService
         */
        $niveauService = $container->get(NiveauService::class);

        $hydrator = new NiveauEnveloppeHydrator();
        $hydrator->setNiveauService($niveauService);
        return $hydrator;
    }
}