<?php

namespace Element\Form\CompetenceType;

use Element\Service\CompetenceType\CompetenceTypeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceTypeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceTypeForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceTypeForm
    {
        /**
         * @var CompetenceTypeService $competenceTypeService
         * @var CompetenceTypeHydrator $hydrator
         */
        $competenceTypeService = $container->get(CompetenceTypeService::class);
        $hydrator = $container->get('HydratorManager')->get(CompetenceTypeHydrator::class);

        $form = new CompetenceTypeForm();
        $form->setCompetenceTypeService($competenceTypeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}