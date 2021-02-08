<?php

namespace Application\Form\AjouterFicheMetier;

use Application\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;

class AjouterFicheMetierFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var AjouterFicheMetierHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AjouterFicheMetierHydrator::class);

        /**
         * @var DomaineService $domaineService
         * @var FicheMetierService $ficheMetierService
         */
        $domaineService = $container->get(DomaineService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);

        /** @var AjouterFicheMetierForm $form */
        $form = new AjouterFicheMetierForm();
        $form->setDomaineService($domaineService);
        $form->setFicheMetierService($ficheMetierService);
        $form->setHydrator($hydrator);
        return $form;
    }

}