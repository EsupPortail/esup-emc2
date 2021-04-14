<?php

namespace Formation\Controller;

 use Formation\Service\Formation\FormationService;
 use Formation\Service\FormationGroupe\FormationGroupeService;
 use Formation\Service\FormationInstance\FormationInstanceService;
 use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeService;
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
          */
         $formationService = $container->get(FormationService::class);
         $formationGroupeService = $container->get(FormationGroupeService::class);
         $formationInstanceService = $container->get(FormationInstanceService::class);
         $formationInstanceJourneeService = $container->get(FormationInstanceJourneeService::class);

         $controller = new ImportationLagafController();
         $controller->setFormationService($formationService);
         $controller->setFormationGroupeService($formationGroupeService);
         $controller->setFormationInstanceService($formationInstanceService);
         $controller->setFormationInstanceJourneeService($formationInstanceJourneeService);
         return $controller;
     }
 }