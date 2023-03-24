<?php

namespace Element\Form\Niveau;

use Element\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class NiveauFormFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : NiveauForm
    {
        /**
         * @var NiveauService $NiveauService
         * @var NiveauHydrator $NiveauHydrator
         */
        $NiveauService = $container->get(NiveauService::class);
        $NiveauHydrator = $container->get('HydratorManager')->get(NiveauHydrator::class);

        $form = new NiveauForm();
        $form->setNiveauService($NiveauService);
        $form->setHydrator($NiveauHydrator);
        return $form;
    }
}