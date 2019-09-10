<?php

namespace Application\Form\Agent;

use Interop\Container\ContainerInterface;


class AgentFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var AgentHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentHydrator::class);

        /** @var AgentForm $form */
        $form = new AgentForm();
        $form->setHydrator($hydrator);
        $form->init();
        return $form;
    }
}