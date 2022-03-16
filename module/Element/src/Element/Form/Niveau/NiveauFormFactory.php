<?php

namespace Element\Form\Niveau;

use Element\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class NiveauFormFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauForm
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