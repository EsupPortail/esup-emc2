<?php

namespace Application\Form\AjouterFicheMetier;

use FicheMetier\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;
use Metier\Service\Domaine\DomaineService;
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
         * @var DomaineService $domaineService
         * @var FicheMetierService $ficheMetierService
         */
        $domaineService = $container->get(DomaineService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);

        $form = new AjouterFicheMetierForm();
        $form->setDomaineService($domaineService);
        $form->setFicheMetierService($ficheMetierService);
        $form->setHydrator($hydrator);
        return $form;
    }

}