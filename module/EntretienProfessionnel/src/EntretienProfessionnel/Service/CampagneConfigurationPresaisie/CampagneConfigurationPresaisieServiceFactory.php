<?php

namespace EntretienProfessionnel\Service\CampagneConfigurationPresaisie;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenAutoform\Service\Formulaire\FormulaireReponseService;
use UnicaenRenderer\Service\Macro\MacroService;

class CampagneConfigurationPresaisieServiceFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneConfigurationPresaisieService
    {
        /**
         * @var EntityManager $entityManager
         * @var FormulaireReponseService $formulaireReponseService
         * @var MacroService $macroService
         */
        $entityManager = $container->get('doctrine.entitymanager.orm_default');
        $formulaireReponseService = $container->get(FormulaireReponseService::class);
        $macroService = $container->get(MacroService::class);

        $service = new CampagneConfigurationPresaisieService();
        $service->setObjectManager($entityManager);
        $service->setFormulaireReponseService($formulaireReponseService);
        $service->setMacroService($macroService);
        return $service;
    }
}
