<?php

namespace Application\Form\FonctionDestination;

use Interop\Container\ContainerInterface;

class FonctionDestinationFormFactory
{
    /**
     * @param ContainerInterface $container
     * @return FonctionDestinationForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var FonctionDestinationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FonctionDestinationHydrator::class);

        /** @var FonctionDestinationForm $form */
        $form = new FonctionDestinationForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}