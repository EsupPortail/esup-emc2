<?php

namespace Element\Form\Niveau;

use Element\Service\NiveauMaitrise\NiveauMaitriseService;
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
         * @var NiveauMaitriseService $NiveauService
         * @var NiveauHydrator $NiveauHydrator
         */
        $NiveauService = $container->get(NiveauMaitriseService::class);
        $NiveauHydrator = $container->get('HydratorManager')->get(NiveauHydrator::class);

        $form = new NiveauForm();
        $form->setNiveauMaitriseService($NiveauService);
        $form->setHydrator($NiveauHydrator);
        return $form;
    }
}