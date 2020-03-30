<?php

namespace Application\Form\Expertise;

use Interop\Container\ContainerInterface;

class ExpertiseFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ExpertiseForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ExpertiseHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ExpertiseHydrator::class);

        /** @var ExpertiseForm $form */
        $form = new ExpertiseForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}
