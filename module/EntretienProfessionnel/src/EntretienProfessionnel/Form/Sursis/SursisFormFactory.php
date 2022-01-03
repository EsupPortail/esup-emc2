<?php

namespace EntretienProfessionnel\Form\Sursis;

use Interop\Container\ContainerInterface;

class SursisFormFactory
{
    /**
     * @param ContainerInterface $container
     * @return SursisForm
     */
    public function __invoke(ContainerInterface $container) : SursisForm
    {
        /**
         * @var SursisHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(SursisHydrator::class);

        $form = new SursisForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}