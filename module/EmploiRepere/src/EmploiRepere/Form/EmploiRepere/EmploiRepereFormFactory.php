<?php

namespace EmploiRepere\Form\EmploiRepere;

use EmploiRepere\Service\EmploiRepere\EmploiRepereService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class EmploiRepereFormFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EmploiRepereForm
    {
        /**
         * @var EmploiRepereHydrator $hydrator
         * @var EmploiRepereService $emploiRepereService
         */
        $emploiRepereService = $container->get(EmploiRepereService::class);
        $hydrator = $container->get('HydratorManager')->get(EmploiRepereHydrator::class);

        $form = new EmploiRepereForm();
        $form->setEmploiRepereService($emploiRepereService);
        $form->setHydrator($hydrator);
        return $form;
    }
}
