<?php

namespace Application\Form\Raison;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class RaisonFormFactory {

    /**
     * @param ContainerInterface $container
     * @return RaisonForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : RaisonForm
    {
        /**
         * @var RaisonHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(RaisonHydrator::class);

        $form = new RaisonForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}