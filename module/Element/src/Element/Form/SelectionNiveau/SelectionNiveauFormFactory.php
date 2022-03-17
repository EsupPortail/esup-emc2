<?php

namespace Element\Form\SelectionNiveau;

use Element\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class SelectionNiveauFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionNiveauForm
     */
    public function __invoke(ContainerInterface $container)
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