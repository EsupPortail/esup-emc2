<?php

namespace Element\Form\CompetenceDiscipline;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceDisciplineFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return CompetenceDisciplineForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CompetenceDisciplineForm
    {
        /** @var CompetenceDisciplineHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceDisciplineHydrator::class);

        $form = new CompetenceDisciplineForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}