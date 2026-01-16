<?php

namespace FicheMetier\Form\MissionPrincipale;

use Carriere\Service\Niveau\NiveauService;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class MissionPrincipaleFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MissionPrincipaleForm
    {
        /**
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         * @var NiveauService $niveauService
         */
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);
        $niveauService = $container->get(NiveauService::class);

        /** @var MissionPrincipaleHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MissionPrincipaleHydrator::class);

        $form = new MissionPrincipaleForm();
        $form->setFamilleProfessionnelleService($familleProfessionnelleService);
        $form->setNiveauService($niveauService);
        $form->setHydrator($hydrator);

        $array = [];
        $niveaux = $niveauService->getNiveaux();
        foreach ($niveaux as $niveau) $array[$niveau->getId()] = $niveau;

        $form->niveaux = $array;

        return $form;
    }
}
