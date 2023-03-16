<?php

namespace Metier\Form\Referentiel;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ReferentielFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferentielForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ReferentielForm
    {
        /** @var ReferentielHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ReferentielHydrator::class);

        /** @var ReferentielForm $form */
        $form = new ReferentielForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}