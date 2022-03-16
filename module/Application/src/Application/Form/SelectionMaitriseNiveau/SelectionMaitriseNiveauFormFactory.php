<?php

namespace Application\Form\SelectionMaitriseNiveau;

use Element\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class SelectionMaitriseNiveauFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionMaitriseNiveauForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var NiveauService $MaitriseNiveauService
         * @var SelectionMaitriseNiveauHydrator $hydrator
         */
        $MaitriseNiveauService = $container->get(NiveauService::class);
        $hydrator = $container->get('HydratorManager')->get(SelectionMaitriseNiveauHydrator::class);

        $form = new SelectionMaitriseNiveauForm();
        $form->setNiveauService($MaitriseNiveauService);
        $form->setHydrator($hydrator);
        return $form;
    }
}