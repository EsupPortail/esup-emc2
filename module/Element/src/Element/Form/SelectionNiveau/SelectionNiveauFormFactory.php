<?php

namespace Element\Form\SelectionNiveau;

use Element\Service\NiveauMaitrise\NiveauMaitriseService;
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
         * @var NiveauMaitriseService $niveauService
         * @var SelectionNiveauHydrator $hydrator
         */
        $niveauMaitriseService = $container->get(NiveauMaitriseService::class);
        $hydrator = $container->get('HydratorManager')->get(SelectionNiveauHydrator::class);

        $form = new SelectionNiveauForm();
        $form->setNiveauMaitriseService($niveauMaitriseService);
        $form->setHydrator($hydrator);
        return $form;
    }
}