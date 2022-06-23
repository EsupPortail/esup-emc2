<?php

namespace Application\Service\FichePoste;

use Application\Service\Agent\AgentService;
use Application\Service\SpecificitePoste\SpecificitePosteService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use Structure\Service\Structure\StructureService;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class FichePosteServiceFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         * @var EtatService $etatService
         * @var SpecificitePosteService $specificitePosteService
         * @var StructureService $structureService
         * @var ValidationInstanceService $validationInstanceService
         * @var ValidationTypeService $validationTypeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);
        $etatService = $container->get(EtatService::class);
        $specificitePosteService = $container->get(SpecificitePosteService::class);
        $structureService = $container->get(StructureService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);

        /** @var FichePosteService $service */
        $service = new FichePosteService();
        $service->setEntityManager($entityManager);
        $service->setAgentService($agentService);
        $service->setEtatService($etatService);
        $service->setSpecificitePosteService($specificitePosteService);
        $service->setStructureService($structureService);
        $service->setValidationInstanceService($validationInstanceService);
        $service->setValidationTypeService($validationTypeService);
        return $service;
    }
}