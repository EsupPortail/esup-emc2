<?php

namespace Application\Form\MaitriseNiveau;

use Application\Service\MaitriseNiveau\MaitriseNiveauService;
use Interop\Container\ContainerInterface;

class MaitriseNiveauFormFactory {

    /**
     * @param ContainerInterface $container
     * @return MaitriseNiveauForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MaitriseNiveauService $MaitriseNiveauService
         * @var MaitriseNiveauHydrator $MaitriseNiveauHydrator
         */
        $MaitriseNiveauService = $container->get(MaitriseNiveauService::class);
        $MaitriseNiveauHydrator = $container->get('HydratorManager')->get(MaitriseNiveauHydrator::class);

        $form = new MaitriseNiveauForm();
        $form->setMaitriseNiveauService($MaitriseNiveauService);
        $form->setHydrator($MaitriseNiveauHydrator);
        return $form;
    }
}