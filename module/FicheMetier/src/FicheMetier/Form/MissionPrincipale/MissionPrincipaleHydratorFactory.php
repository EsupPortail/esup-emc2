<?php

namespace FicheMetier\Form\MissionPrincipale;

use Carriere\Service\Niveau\NiveauService;
use FicheMetier\Service\MissionActivite\MissionActiviteService;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\Referentiel\ReferentielService;

class MissionPrincipaleHydratorFactory {

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MissionPrincipaleHydrator
    {
        /**
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         * @var NiveauService $niveauService
         * @var ReferentielService $referentielService
         */
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);
        $niveauService = $container->get(NiveauService::class);
        $referentielService = $container->get(ReferentielService::class);

        $hydrator = new MissionPrincipaleHydrator();
        $hydrator->setFamilleProfessionnelleService($familleProfessionnelleService);
        $hydrator->setNiveauService($niveauService);
        $hydrator->setReferentielService($referentielService);
        return $hydrator;
    }
}
