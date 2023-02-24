<?php

namespace FicheMetier\Form\SelectionFicheMetier;

use FicheMetier\Service\FicheMetier\FicheMetierService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionFicheMetierFormFactory
{
    /**
     * @param ContainerInterface $container
     * @return SelectionFicheMetierForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SelectionFicheMetierForm
    {
        /**
         * @var FicheMetierService $ficheMetierService
         */
        $ficheMetierService = $container->get(FicheMetierService::class);

        $form = new SelectionFicheMetierForm();
        $form->setFicheMetierService($ficheMetierService);
        return $form;
    }
}