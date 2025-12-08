<?php

namespace FicheMetier\Form\CodeFonction;

use Carriere\Service\NiveauFonction\NiveauFonctionService;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CodeFonctionFormFactory {

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CodeFonctionForm
    {
        /**
         * @var FamilleProfessionnelleService $familleProfessionneleService
         * @var NiveauFonctionService $niveauFonctionService
         * @var CodeFonctionHydrator $hydrator
         */
        $familleProfessionneleService = $container->get(FamilleProfessionnelleService::class);
        $niveauFonctionService = $container->get(NiveauFonctionService::class);
        $hydrator = $container->get('HydratorManager')->get(CodeFonctionHydrator::class);

        $form = new CodeFonctionForm();
        $form->setFamilleProfessionnelleService($familleProfessionneleService);
        $form->setNiveauFonctionService($niveauFonctionService);
        $form->setHydrator($hydrator);
        return $form;
    }
}