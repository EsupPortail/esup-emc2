<?php

namespace Metier\Form\MetierNiveau;

use Interop\Container\ContainerInterface;

class MetierNiveauFormFactory {

    /**
     * @param ContainerInterface $container
     * @return MetierNiveauForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var MetierNiveauHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MetierNiveauHydrator::class);

        $form = new MetierNiveauForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}