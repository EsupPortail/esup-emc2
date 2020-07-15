<?php

namespace Application\Form\ApplicationGroupe;

use Interop\Container\ContainerInterface;

class ApplicationGroupeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ApplicationGroupeForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ApplicationGroupeHydrator $hydrator  */
        $hydrator = $container->get('HydratorManager')->get(ApplicationGroupeHydrator::class);

        /** @var ApplicationGroupeForm $form */
        $form = new ApplicationGroupeForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}