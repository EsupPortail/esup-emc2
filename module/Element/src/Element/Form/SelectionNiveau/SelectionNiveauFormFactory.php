<?php

namespace Element\Form\SelectionNiveau;

use Element\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionNiveauFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionNiveauForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionNiveauForm
    {
        /**
         * @var NiveauService $niveauService
         * @var SelectionNiveauHydrator $hydrator
         */
        $niveauService = $container->get(NiveauService::class);
        $hydrator = $container->get('HydratorManager')->get(SelectionNiveauHydrator::class);

        $form = new SelectionNiveauForm();
        $form->setNiveauService($niveauService);
        $form->setHydrator($hydrator);
        return $form;
    }
}