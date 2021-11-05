<?php

namespace EntretienProfessionnel\Service\EntretienProfessionnel;

use Application\Service\Configuration\ConfigurationService;
use Autoform\Service\Formulaire\FormulaireInstanceService;
use Doctrine\ORM\EntityManager;
use EntretienProfessionnel\Service\Delegue\DelegueService;
use Interop\Container\ContainerInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;

class EntretienProfessionnelServiceFactory
{
    /**
     * @param ContainerInterface $container
     * @return EntretienProfessionnelService
     */
    public function __invoke(ContainerInterface $container) : EntretienProfessionnelService
    {
        /**
         * @var EntityManager $entityManager
         * @var ConfigurationService $configurationService
         * @var DelegueService $delegueService
         * @var UserService $userService
         * @var FormulaireInstanceService $formulaireInstanceService
         * @var ParametreService $parametreService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $userService = $container->get(UserService::class);
        $configurationService = $container->get(ConfigurationService::class);
        $delegueService = $container->get(DelegueService::class);
        $formulaireInstanceService = $container->get(FormulaireInstanceService::class);
        $parametreService = $container->get(ParametreService::class);

        $service = new EntretienProfessionnelService();
        $service->setEntityManager($entityManager);
        $service->setUserService($userService);
        $service->setConfigurationService($configurationService);
        $service->setDelegueService($delegueService);
        $service->setFormulaireInstanceService($formulaireInstanceService);
        $service->setParametreService($parametreService);
        return $service;
    }
}