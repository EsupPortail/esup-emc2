<?php

namespace Application\Form\AgentPPP;

use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class AgentPPPFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentPPPForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AgentPPPForm
    {
        /** @var AgentPPPHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentPPPHydrator::class);

        $form = new AgentPPPForm();
        $form->setHydrator($hydrator);
        return $form;
    }
}