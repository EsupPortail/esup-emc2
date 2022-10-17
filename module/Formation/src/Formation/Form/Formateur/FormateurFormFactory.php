<?php

namespace Formation\Form\Formateur;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormateurFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormateurForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FormateurForm
    {
        /** @var FormateurHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(FormateurHydrator::class);

        $form = new FormateurForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}