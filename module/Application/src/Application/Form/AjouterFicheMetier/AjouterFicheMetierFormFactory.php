<?php

namespace Application\Form\AjouterFicheMetier;

use Application\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;

class AjouterFicheMetierFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var AjouterFicheMetierHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AjouterFicheMetierHydrator::class);

        /** @var FicheMetierService $ficheMetierService */
        $ficheMetierService = $container->get(FicheMetierService::class);

        /** @var AjouterFicheMetierForm $form */
        $form = new AjouterFicheMetierForm();
        $form->setFicheMetierService($ficheMetierService);
        $form->setHydrator($hydrator);
        return $form;
    }

}