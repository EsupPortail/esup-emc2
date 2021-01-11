<?php

namespace Application\Controller;

use Application\Form\MetierReference\MetierReferenceForm;
use Application\Form\MetierReferentiel\MetierReferentielForm;
use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Application\Form\Domaine\DomaineForm;
use Application\Form\Metier\MetierForm;
use Application\Service\Domaine\DomaineService;
use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Application\Service\Metier\MetierService;
use Application\Service\MetierReference\MetierReferenceService;
use Application\Service\MetierReferentiel\MetierReferentielService;
use Interop\Container\ContainerInterface;

class MetierControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return MetierController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var FamilleProfessionnelleService $familleService
         * @var MetierService $metierService
         * @var MetierReferenceService $metierReferenceService
         * @var MetierReferentielService $metierReferentielService
         */
        $familleService = $container->get(FamilleProfessionnelleService::class);
        $metierService = $container->get(MetierService::class);
        $metierReferenceService = $container->get(MetierReferenceService::class);
        $metierReferentielService = $container->get(MetierReferentielService::class);

        /**
         * @var MetierForm $metierForm
         * @var ModifierLibelleForm $modifierLibelleForm
         * @var MetierReferenceForm $metierReferenceForm
         * @var MetierReferentielForm $metierReferentielForm
         */
        $metierForm = $container->get('FormElementManager')->get(MetierForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $metierReferenceForm = $container->get('FormElementManager')->get(MetierReferenceForm::class);
        $metierReferentielForm = $container->get('FormElementManager')->get(MetierReferentielForm::class);

        /** @var MetierController $controller */
        $controller = new MetierController();

        $controller->setFamilleProfessionnelleService($familleService);
        $controller->setMetierService($metierService);
        $controller->setMetierReferenceService($metierReferenceService);
        $controller->setMetierReferentielService($metierReferentielService);

        $controller->setMetierForm($metierForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setMetierReferenceForm($metierReferenceForm);
        $controller->setMetierReferentielForm($metierReferentielForm);

        return $controller;
    }
}
