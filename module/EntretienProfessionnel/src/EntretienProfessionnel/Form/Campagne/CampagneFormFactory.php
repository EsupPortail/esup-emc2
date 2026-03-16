<?php

namespace EntretienProfessionnel\Form\Campagne;

use EntretienProfessionnel\Service\Campagne\CampagneService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenAutoform\Service\Formulaire\FormulaireService;
use UnicaenRenderer\Service\Template\TemplateService;

class CampagneFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CampagneForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CampagneForm
    {
        /**
         * @var CampagneService $campagneService
         * @var FormulaireService $formulaireService
         * @var TemplateService $templateService
         * @var CampagneHydrator $hydrator
         */
        $campagneService = $container->get(CampagneService::class);
        $formulaireService = $container->get(FormulaireService::class);
        $templateService = $container->get(TemplateService::class);
        $hydrator = $container->get('HydratorManager')->get(CampagneHydrator::class);

        $form = new CampagneForm();
        $form->setCampagneService($campagneService);
        $form->setFormulaireService($formulaireService);
        $form->setTemplateService($templateService);
        $form->setHydrator($hydrator);
        return $form;
    }
}