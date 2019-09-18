<?php

namespace Application\Form\AjouterFicheMetier;

use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Application\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;

class AjouterFicheMetierFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var AjouterFicheMetierHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AjouterFicheMetierHydrator::class);

        /**
         * @var FamilleProfessionnelleService $familleService
         * @var FicheMetierService $ficheMetierService
         */
        $familleService = $container->get(FamilleProfessionnelleService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);

        /** @var AjouterFicheMetierForm $form */
        $form = new AjouterFicheMetierForm();
        $form->setFamilleProfessionnelleService($familleService);
        $form->setFicheMetierService($ficheMetierService);
        $form->setHydrator($hydrator);
        return $form;
    }

}