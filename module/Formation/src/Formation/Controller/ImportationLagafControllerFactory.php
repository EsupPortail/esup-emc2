<?php

namespace Formation\Controller;

 use Application\Entity\Db\Interfaces\HasSourceInterface;
 use Formation\Service\Formation\FormationService;
 use Formation\Service\FormationGroupe\FormationGroupeService;
 use Formation\Service\FormationInstance\FormationInstanceService;
 use Formation\Service\FormationInstanceFrais\FormationInstanceFraisService;
 use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
 use Formation\Service\HasFormationCollection\HasFormationCollectionService;
 use Formation\Service\Presence\PresenceService;
 use Formation\Service\Seance\SeanceService;
 use Formation\Service\Stagiaire\StagiaireService;
 use Interop\Container\ContainerInterface;
 use UnicaenDbImport\Entity\Db\Service\Source\SourceService;
 use UnicaenEtat\Service\Etat\EtatService;

 class ImportationLagafControllerFactory {

     /**
      * @param ContainerInterface $container
      * @return ImportationLagafController
      */
     public function __invoke(ContainerInterface $container)
     {
         /**
          * @var EtatService $etatService
          * @var FormationService $formationService
          * @var FormationGroupeService $formationGroupeService
          * @var FormationInstanceService $formationInstanceService
          * @var SeanceService $seanceService
          * @var FormationInstanceInscritService $formationInstanceInscritService
          * @var FormationInstanceFraisService $formationInstanceFraisService
          * @var PresenceService $presenceService
          * @var HasFormationCollectionService $hasFormationCollectionService
          * @var StagiaireService $stagiaireService
          */
         $etatService = $container->get(EtatService::class);
         $formationService = $container->get(FormationService::class);
         $formationGroupeService = $container->get(FormationGroupeService::class);
         $formationInstanceService = $container->get(FormationInstanceService::class);
         $seanceService = $container->get(SeanceService::class);
         $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
         $formationInstanceFraisService = $container->get(FormationInstanceFraisService::class);
         $presenceService = $container->get(PresenceService::class);
         $hasFormationCollectionService = $container->get(HasFormationCollectionService::class);
         $stagiaireService = $container->get(StagiaireService::class);

         /**
          * @var SourceService $sourceService
          */
         $entityManager = $container->get('doctrine.entitymanager.orm_default');
         $sourceService = $container->get(SourceService::class);
         $sourceService->setEntityManager($entityManager);
         $lagaf = $sourceService->getRepository()->findOneBy(['code' => HasSourceInterface::SOURCE_LAGAF]);

         $controller = new ImportationLagafController();
         $controller->setEtatService($etatService);
         $controller->setFormationService($formationService);
         $controller->setFormationGroupeService($formationGroupeService);
         $controller->setFormationInstanceService($formationInstanceService);
         $controller->setSeanceService($seanceService);
         $controller->setFormationInstanceInscritService($formationInstanceInscritService);
         $controller->setFormationInstanceFraisService($formationInstanceFraisService);
         $controller->setPresenceService($presenceService);
         $controller->setHasFormationCollectionService($hasFormationCollectionService);
         $controller->setStagiaireService($stagiaireService);
         $controller->sourceLagaf = $lagaf;
         return $controller;
     }
 }