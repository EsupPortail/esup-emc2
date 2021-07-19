<?php

namespace Application\Form\NiveauEnveloppe;

use Application\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class NiveauEnveloppeHydratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return NiveauEnveloppeHydrator
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