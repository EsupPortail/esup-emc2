<?php

namespace Application\Form\Poste;

use Application\Service\Domaine\DomaineService;
use Application\Service\Fonction\FonctionService;
use Application\Service\Immobilier\ImmobilierService;
use Application\Service\Structure\StructureService;
use Application\Service\Agent\AgentService;
use Application\Service\RessourceRh\RessourceRhService;
use Zend\ServiceManager\ServiceLocatorInterface;

class PosteHydratorFactory {

    public function __invoke(ServiceLocatorInterface $serviceLocator)
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();
        /**
         * @var AgentService $agentService
         * @var DomaineService $domaineService
         * @var FonctionService $fonctionService
         * @var StructureService $structureService
         * @var RessourceRhService $ressourceService
         * @var ImmobilierService $immobilierService
         */
        $agentService = $parentLocator->get(AgentService::class);
        $domaineService = $parentLocator->get(DomaineService::class);
        $fonctionService = $parentLocator->get(FonctionService::class);
        $structureService = $parentLocator->get(StructureService::class);
        $ressourceService = $parentLocator->get(RessourceRhService::class);
        $immobilierService = $parentLocator->get(ImmobilierService::class);


        $hydrator = new PosteHydrator();
        $hydrator->setAgentService($agentService);
        $hydrator->setDomaineService($domaineService);
        $hydrator->setStructureService($structureService);
        $hydrator->setFonctionService($fonctionService);
        $hydrator->setRessourceRhService($ressourceService);
        $hydrator->setImmobilierService($immobilierService);

        return $hydrator;
    }
}