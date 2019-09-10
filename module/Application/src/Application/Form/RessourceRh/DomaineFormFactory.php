<?php

namespace Application\Form\RessourceRh;

use Application\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Interop\Container\ContainerInterface;

class DomaineFormFactory {

    public function __invoke(ContainerInterface $container)
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