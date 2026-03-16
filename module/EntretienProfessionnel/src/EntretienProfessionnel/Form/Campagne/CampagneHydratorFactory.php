<?php


namespace EntretienProfessionnel\Form\Campagne;

use EntretienProfessionnel\Service\Campagne\CampagneService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenAutoform\Service\Formulaire\FormulaireService;
use UnicaenRenderer\Service\Template\TemplateService;

class CampagneHydratorFactory {

    /**
     * @param ContainerInterface $container
     * @return CampagneHydrator
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CampagneHydrator
    {
        /**
         * @var CampagneService $campagneService
         * @var FormulaireService $formulaireService
         * @var TemplateService $templateService
         */
        $campagneService = $container->get(CampagneService::class);
        $formulaireService = $container->get(FormulaireService::class);
        $templateService = $container->get(TemplateService::class);

        $hydrator = new CampagneHydrator();
        $hydrator->setCampagneService($campagneService);
        $hydrator->setFormulaireService($formulaireService);
        $hydrator->setTemplateService($templateService);
        return $hydrator;
    }
}