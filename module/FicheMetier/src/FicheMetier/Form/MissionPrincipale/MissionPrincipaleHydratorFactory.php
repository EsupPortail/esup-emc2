<?php

namespace FicheMetier\Form\MissionPrincipale;

use Carriere\Service\Niveau\NiveauService;
use FicheMetier\Service\MissionActivite\MissionActiviteService;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionPrincipaleHydratorFactory {

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MissionPrincipaleHydrator
    {
        /**
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         * @var MissionActiviteService $missionActiviteService
         * @var NiveauService $niveauService
         */
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);
        $missionActiviteService = $container->get(MissionActiviteService::class);
        $niveauService = $container->get(NiveauService::class);

        $hydrator = new MissionPrincipaleHydrator();
        $hydrator->setFamilleProfessionnelleService($familleProfessionnelleService);
        $hydrator->setMissionActiviteService($missionActiviteService);
        $hydrator->setNiveauService($niveauService);
        return $hydrator;
    }
}
