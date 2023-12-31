<?php

namespace Metier\Form\Domaine;

use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class DomaineFormFactory {

    /**
     * @param ContainerInterface $container
     * @return DomaineForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : DomaineForm
    {
        /**
         * @var FamilleProfessionnelleService $familleService
         */
        $familleService = $container->get(FamilleProfessionnelleService::class);

        /** @var DomaineHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(DomaineHydrator::class);

        $form = new DomaineForm();
        $form->setFamilleProfessionnelleService($familleService);
        $form->init();
        $form->setHydrator($hydrator);

        return $form;
    }
}