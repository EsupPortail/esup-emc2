<?php

namespace Application\Form\SelectionMaitriseNiveau;

use Application\Service\MaitriseNiveau\MaitriseNiveauService;
use Interop\Container\ContainerInterface;

class SelectionMaitriseNiveauFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionMaitriseNiveauForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MaitriseNiveauService $MaitriseNiveauService
         * @var SelectionMaitriseNiveauHydrator $hydrator
         */
        $MaitriseNiveauService = $container->get(MaitriseNiveauService::class);
        $hydrator = $container->get('HydratorManager')->get(SelectionMaitriseNiveauHydrator::class);

        $form = new SelectionMaitriseNiveauForm();
        $form->setMaitriseNiveauService($MaitriseNiveauService);
        $form->setHydrator($hydrator);
        return $form;
    }
}