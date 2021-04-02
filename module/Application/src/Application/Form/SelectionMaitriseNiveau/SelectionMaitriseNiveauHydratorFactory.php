<?php

namespace Application\Form\SelectionMaitriseNiveau;

use Application\Service\MaitriseNiveau\MaitriseNiveauService;
use Interop\Container\ContainerInterface;

class SelectionMaitriseNiveauHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionMaitriseNiveauHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MaitriseNiveauService $MaitriseNiveauService
         */
        $MaitriseNiveauService = $container->get(MaitriseNiveauService::class);

        $hydrator = new SelectionMaitriseNiveauHydrator();
        $hydrator->setMaitriseNiveauService($MaitriseNiveauService);
        return $hydrator;
    }
}