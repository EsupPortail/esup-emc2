<?php

namespace Formation\Form\Demande2Formation;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Demande2FormationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return Demande2FormationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : Demande2FormationForm
    {
        /** @var Demande2FormationHydrator $hydrator  */
        $hydrator = $container->get('HydratorManager')->get(Demande2FormationHydrator::class);

        $form = new Demande2FormationForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}