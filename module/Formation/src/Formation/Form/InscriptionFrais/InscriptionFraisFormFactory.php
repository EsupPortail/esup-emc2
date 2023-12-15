<?php

namespace Formation\Form\InscriptionFrais;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class InscriptionFraisFormFactory
{
    /**
     * @param ContainerInterface $container
     * @return InscriptionFraisForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): InscriptionFraisForm
    {
        /** @var InscriptionFraisHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(InscriptionFraisHydrator::class);

        $form = new InscriptionFraisForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}