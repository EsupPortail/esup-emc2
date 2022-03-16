<?php

namespace Application\Form\SelectionMaitriseNiveau;

use Element\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class SelectionMaitriseNiveauHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionMaitriseNiveauHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var NiveauService $MaitriseNiveauService
         */
        $MaitriseNiveauService = $container->get(NiveauService::class);

        $hydrator = new SelectionMaitriseNiveauHydrator();
        $hydrator->setNiveauService($MaitriseNiveauService);
        return $hydrator;
    }
}