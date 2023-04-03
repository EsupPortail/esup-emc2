<?php

namespace EntretienProfessionnel\Service\EntretienProfessionnel;

use Application\Service\Agent\AgentService;
use Application\Service\Configuration\ConfigurationService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenAutoform\Service\Formulaire\FormulaireInstanceService;
use Doctrine\ORM\EntityManager;
use Interop\Container\ContainerInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceService;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class EntretienProfessionnelServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelService
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): EntretienProfessionnelService
    {
        /**
         * @var EntityManager $entityManager
         * @var AgentService $agentService
         * @var ConfigurationService $configurationService
         * @var FormulaireInstanceService $formulaireInstanceService
         * @var ParametreService $parametreService
         * @var ValidationInstanceService $validationInstanceService
         * @var ValidationTypeService $validationTypeService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $agentService = $container->get(AgentService::class);
        $configurationService = $container->get(ConfigurationService::class);
        $formulaireInstanceService = $container->get(FormulaireInstanceService::class);
        $parametreService = $container->get(ParametreService::class);
        $validationInstanceService = $container->get(ValidationInstanceService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);

        $service = new EntretienProfessionnelService();
        $service->setEntityManager($entityManager);
        $service->setAgentService($agentService);
        $service->setConfigurationService($configurationService);
        $service->setFormulaireInstanceService($formulaireInstanceService);
        $service->setParametreService($parametreService);
        $service->setValidationInstanceService($validationInstanceService);
        $service->setValidationTypeService($validationTypeService);
        return $service;
    }
}