<?php

namespace FicheReferentiel\Form\Importation;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ImportationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ImportationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ImportationForm
    {
        /** @var ImportationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ImportationHydrator::class);

        $form = new ImportationForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}