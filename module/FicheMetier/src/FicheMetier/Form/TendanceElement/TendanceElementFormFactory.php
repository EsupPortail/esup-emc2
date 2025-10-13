<?php

namespace FicheMetier\Form\TendanceElement;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class TendanceElementFormFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): TendanceElementForm
    {
        /**
         * @var TendanceElementHydrator $thematiqueElementHydrator
         */
        $thematiqueElementHydrator = $container->get('HydratorManager')->get(TendanceElementHydrator::class);

        $form = new TendanceElementForm();
        $form->setHydrator($thematiqueElementHydrator);
        return $form;
    }

}