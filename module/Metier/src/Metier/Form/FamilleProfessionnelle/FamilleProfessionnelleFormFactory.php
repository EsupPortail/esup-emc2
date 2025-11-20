<?php

namespace Metier\Form\FamilleProfessionnelle;

use Carriere\Service\Correspondance\CorrespondanceService;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class FamilleProfessionnelleFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FamilleProfessionnelleForm
    {
        /**
         * @var CorrespondanceService $correspondanceService
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         * @var FamilleProfessionnelleHydrator $hydrator
         */
        $correspondanceService = $container->get(CorrespondanceService::class);
        $familleProfessionnelleService = $container->get(FamilleprofessionnelleService::class);
        $hydrator = $container->get('HydratorManager')->get(FamilleProfessionnelleHydrator::class);

        $form = new FamilleProfessionnelleForm();
        $form->setCorrespondanceService($correspondanceService);
        $form->setFamilleProfessionnelleService($familleProfessionnelleService);
        $form->setHydrator($hydrator);
        return $form;
    }

}
