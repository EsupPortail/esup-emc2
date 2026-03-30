<?php

namespace EntretienProfessionnel\Form\CampagneConfigurationIndicateur;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class CampagneConfigurationIndicateurFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CampagneConfigurationIndicateurForm
    {
        /**
         * @var CampagneConfigurationIndicateurHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(CampagneConfigurationIndicateurHydrator::class);

        $form = new CampagneConfigurationIndicateurForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}
