<?php

namespace Application\Form\FicheMetierImportation;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class FicheMetierImportationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return FicheMetierImportationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FicheMetierImportationForm
    {
        /** @var FichierMetierImportationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FichierMetierImportationHydrator::class);

        $form = new FicheMetierImportationForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}