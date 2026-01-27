<?php

namespace Carriere\Form\SelectionnerFamilleProfessionnelle;

use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerFamilleProfessionnelleFormFactory {

    /**
     * @param ContainerInterface $container
     * @return SelectionnerFamilleProfessionnelleForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : SelectionnerFamilleProfessionnelleForm
    {
        /** @var SelectionnerFamilleProfessionnelleHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(SelectionnerFamilleProfessionnelleHydrator::class);

        /**
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         */
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);

        $form = new SelectionnerFamilleProfessionnelleForm();
        $form->setFamilleProfessionnelleService($familleProfessionnelleService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}