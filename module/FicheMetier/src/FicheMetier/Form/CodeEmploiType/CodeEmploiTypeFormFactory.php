<?php

namespace FicheMetier\Form\CodeEmploiType;

use Carriere\Service\FonctionType\FonctionTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CodeEmploiTypeFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CodeEmploiTypeForm
    {
        /**
         * @var FonctionTypeService $fonctionTypeService
         * @var CodeEmploiTypeHydrator $hydrator
         */
        $fonctionTypeService = $container->get(FonctionTypeService::class);
        $hydrator = $container->get('HydratorManager')->get(CodeEmploiTypeHydrator::class);

        $form = new CodeEmploiTypeForm();
        $form->setFonctionTypeService($fonctionTypeService);
        $form->setHydrator($hydrator);
        return $form;

    }
}
