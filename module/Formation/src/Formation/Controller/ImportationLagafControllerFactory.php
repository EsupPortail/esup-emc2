<?php

namespace Formation\Controller;

 use Formation\Service\Formation\FormationService;
 use Formation\Service\FormationGroupe\FormationGroupeService;
 use Formation\Service\FormationInstance\FormationInstanceService;
 use Formation\Service\FormationInstanceFrais\FormationInstanceFraisService;
 use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
 use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeService;
 use Formation\Service\FormationInstancePresence\FormationInstancePresenceService;
 use Formation\Service\HasFormationCollection\HasFormationCollectionService;
 use Formation\Service\Stagiaire\StagiaireService;
 use Interop\Container\ContainerInterface;
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
          * @var FormationInstanceJourneeService $formationInstanceJourneeService
          * @var FormationInstanceInscritService $formationInstanceInscritService
          * @var FormationInstanceFraisService $formationInstanceFraisService
          * @var FormationInstancePresenceService $formationInstancePresenceService
          * @var HasFormationCollectionService $hasFormationCollectionService
          * @var StagiaireService $stagiaireService
          */
         $etatService = $container->get(EtatService::class);
         $formationService = $container->get(FormationService::class);
         $formationGroupeService = $container->get(FormationGroupeService::class);
         $formationInstanceService = $container->get(FormationInstanceService::class);
         $formationInstanceJourneeService = $container->get(FormationInstanceJourneeService::class);
         $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
         $formationInstanceFraisService = $container->get(FormationInstanceFraisService::class);
         $formationInstancePresenceService = $container->get(FormationInstancePresenceService::class);
         $hasFormationCollectionService = $container->get(HasFormationCollectionService::class);
         $stagiaireService = $container->get(StagiaireService::class);

         $controller = new ImportationLagafController();
         $controller->setEtatService($etatService);
         $controller->setFormationService($formationService);
         $controller->setFormationGroupeService($formationGroupeService);
         $controller->setFormationInstanceService($formationInstanceService);
         $controller->setFormationInstanceJourneeService($formationInstanceJourneeService);
         $controller->setFormationInstanceInscritService($formationInstanceInscritService);
         $controller->setFormationInstanceFraisService($formationInstanceFraisService);
         $controller->setFormationInstancePresenceService($formationInstancePresenceService);
         $controller->setHasFormationCollectionService($hasFormationCollectionService);
         $controller->setStagiaireService($stagiaireService);
         return $controller;
     }
 }