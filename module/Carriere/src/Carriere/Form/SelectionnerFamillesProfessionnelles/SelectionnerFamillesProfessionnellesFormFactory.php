<?php

namespace Carriere\Form\SelectionnerFamillesProfessionnelles;

use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerFamillesProfessionnellesFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionnerFamillesProfessionnellesForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SelectionnerFamillesProfessionnellesForm
    {
        /** @var SelectionnerFamillesProfessionnellesHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(SelectionnerFamillesProfessionnellesHydrator::class);

        /**
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         */
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);

        $form = new SelectionnerFamillesProfessionnellesForm();
        $form->setFamilleProfessionnelleService($familleProfessionnelleService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}