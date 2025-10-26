<?php

namespace FicheMetier\Form\CodeEmploiType;

use Carriere\Service\FonctionType\FonctionTypeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CodeEmploiTypeHydratorFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CodeEmploiTypeHydrator
    {
        /**
         * @var FonctionTypeService $fonctionTypeService
         */
        $fonctionTypeService = $container->get(FonctionTypeService::class);

        $hydrator = new CodeEmploiTypeHydrator();
        $hydrator->setFonctionTypeService($fonctionTypeService);
        return $hydrator;

    }
}

