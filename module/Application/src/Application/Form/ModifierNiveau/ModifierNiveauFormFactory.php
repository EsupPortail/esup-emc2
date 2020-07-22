<?php

namespace Application\Form\ModifierNiveau;

use Interop\Container\ContainerInterface;

class ModifierNiveauFormFactory
{
    /**
     * @param ContainerInterface $container
     * @return ModifierNiveauForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var ModifierNiveauHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ModifierNiveauHydrator::class);

        /** @var ModifierNiveauForm $form */
        $form = new ModifierNiveauForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}