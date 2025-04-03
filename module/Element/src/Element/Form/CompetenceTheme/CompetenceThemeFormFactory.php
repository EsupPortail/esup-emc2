<?php

namespace Element\Form\CompetenceTheme;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class CompetenceThemeFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return CompetenceThemeForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CompetenceThemeForm
    {
        /** @var CompetenceThemeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(CompetenceThemeHydrator::class);

        $form = new CompetenceThemeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}