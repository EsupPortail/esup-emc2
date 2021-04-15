<?php

namespace Formation\Controller;

 use Formation\Service\Formation\FormationService;
 use Formation\Service\FormationGroupe\FormationGroupeService;
 use Formation\Service\FormationInstance\FormationInstanceService;
 use Formation\Service\FormationInstanceFrais\FormationInstanceFraisService;
 use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
 use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeService;
 use Formation\Service\FormationInstancePresence\FormationInstancePresenceService;
 use Formation\Service\Stagiaire\StagiaireService;
 use Interop\Container\ContainerInterface;

 class ImportationLagafControllerFactory {

     /**
      * @param ContainerInterface $container
      * @return ImportationLagafController
      */
     public function __invoke(ContainerInterface $container)
     {
         /**
          * @var FormationService $formationService
          * @var FormationGroupeService $formationGroupeService
          * @var FormationInstanceService $formationInstanceService
          * @var FormationInstanceJourneeService $formationInstanceJourneeService
          * @var FormationInstanceInscritService $formationInstanceInscritService
          * @var FormationInstanceFraisService $formationInstanceFraisService
          * @var FormationInstancePresenceService $formationInstancePresenceService
          * @var StagiaireService $stagiaireService
          */
         $formationService = $container->get(FormationService::class);
         $formationGroupeService = $container->get(FormationGroupeService::class);
         $formationInstanceService = $container->get(FormationInstanceService::class);
         $formationInstanceJourneeService = $container->get(FormationInstanceJourneeService::class);
         $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
         $formationInstanceFraisService = $container->get(FormationInstanceFraisService::class);
         $formationInstancePresenceService = $container->get(FormationInstancePresenceService::class);
         $stagiaireService = $container->get(StagiaireService::class);

         $controller = new ImportationLagafController();
         $controller->setFormationService($formationService);
         $controller->setFormationGroupeService($formationGroupeService);
         $controller->setFormationInstanceService($formationInstanceService);
         $controller->setFormationInstanceJourneeService($formationInstanceJourneeService);
         $controller->setFormationInstanceInscritService($formationInstanceInscritService);
         $controller->setFormationInstanceFraisService($formationInstanceFraisService);
         $controller->setFormationInstancePresenceService($formationInstancePresenceService);
         $controller->setStagiaireService($stagiaireService);
         return $controller;
     }
 }