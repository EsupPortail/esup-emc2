<?php

namespace Element\Form\CompetenceReferentiel;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceReferentielFormFactory {

    /**
     * @param ContainerInterface $container
     * @return CompetenceReferentielForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CompetenceReferentielForm
    {
        /** @var CompetenceReferentielHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceReferentielHydrator::class);

        /** @var CompetenceReferentielForm $form */
        $form = new CompetenceReferentielForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}