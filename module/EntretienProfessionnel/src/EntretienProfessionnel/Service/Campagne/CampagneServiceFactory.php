<?php

namespace EntretienProfessionnel\Service\Campagne;

use Agent\Service\AgentAffectation\AgentAffectationService;
use Agent\Service\AgentGrade\AgentGradeService;
use Agent\Service\AgentStatut\AgentStatutService;
use Agent\Service\Agent\AgentService;
use Doctrine\ORM\EntityManager;
use EntretienProfessionnel\Service\AgentForceSansObligation\AgentForceSansObligationService;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Service\Structure\StructureService;
use Structure\Service\StructureAgentForce\StructureAgentForceService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenParametre\Service\Parametre\ParametreService;

class CampagneServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @return CampagneService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneService
    {
        /**
         * @var EntityManager $entityManager
         * @var EntretienProfessionnelService $entretienProfessionnelService
         * @var AgentService $agentService
         * @var AgentAffectationService $agentAffectationService
         * @var AgentForceSansObligationService $agentForceService
         * @var AgentGradeService $agentGradeService
         * @var AgentStatutService $agentStatutService
         * @var EtatTypeService $etatTypeService
         * @var ParametreService $parametreService
         * @var StructureService $structureService
         * @var StructureAgentForceService $structureAgentForceService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $agentForceService = $container->get(AgentForceSansObligationService::class);
        $agentGradeService = $container->get(AgentGradeService::class);
        $agentStatutService = $container->get(AgentStatutService::class);
        $entretienProfessionnelService = $container->get(EntretienProfessionnelService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $parametreService = $container->get(ParametreService::class);
        $structureService = $container->get(StructureService::class);
        $structureAgentForceService = $container->get(StructureAgentForceService::class);

        $service = new CampagneService();
        $service->setObjectManager($entityManager);
        $service->setAgentService($agentService);
        $service->setAgentAffectationService($agentAffectationService);
        $service->setAgentForceSansObligationService($agentForceService);
        $service->setAgentGradeService($agentGradeService);
        $service->setAgentStatutService($agentStatutService);
        $service->setEntretienProfessionnelService($entretienProfessionnelService);
        $service->setEtatTypeService($etatTypeService);
        $service->setParametreService($parametreService);
        $service->setStructureService($structureService);
        $service->setStructureAgentForceService($structureAgentForceService);
        return $service;
    }
}