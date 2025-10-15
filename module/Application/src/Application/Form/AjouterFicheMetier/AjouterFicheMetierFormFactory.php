<?php

namespace Application\Form\AjouterFicheMetier;

use FicheMetier\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AjouterFicheMetierFormFactory {

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AjouterFicheMetierForm
    {
        /** @var AjouterFicheMetierHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AjouterFicheMetierHydrator::class);

        /**
         * @var FicheMetierService $ficheMetierService
         */
        $ficheMetierService = $container->get(FicheMetierService::class);

        $form = new AjouterFicheMetierForm();
        $form->setFicheMetierService($ficheMetierService);
        $form->setHydrator($hydrator);
        return $form;
    }

}